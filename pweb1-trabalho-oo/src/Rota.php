<?php
declare(strict_types=1);
require_once __DIR__ . '/Aluno.php';
require_once __DIR__ . '/Veiculo.php';

class Rota {
    private string $nome;
    private float $distanciaKm;
    private array $alunos = []; 
    private ?Veiculo $veiculo = null; 

    public function __construct(string $nome, float $distanciaKm) {
        if ($distanciaKm <= 0) {
            throw new Exception("Erro: A distância da rota deve ser maior que zero.");
        }
        $this->nome = $nome;
        $this->distanciaKm = $distanciaKm;
    }

    public function adicionarAluno(Aluno $aluno): void {
        $this->alunos[] = $aluno;
    }

    // Novo método: Remove o aluno pela matrícula e o devolve para ser movido
    public function removerAluno(string $matricula): ?Aluno {
        foreach ($this->alunos as $key => $aluno) {
            if ($aluno->getMatricula() === $matricula) {
                $removido = $this->alunos[$key];
                unset($this->alunos[$key]);
                $this->alunos = array_values($this->alunos); // Reorganiza os índices
                return $removido;
            }
        }
        return null;
    }

    public function vincularVeiculo(Veiculo $veiculo): void {
        $this->veiculo = $veiculo;
    }

    // Novo método: Limpa o veículo da rota
    public function desvincularVeiculo(): void {
        $this->veiculo = null;
    }

    public function getVeiculo(): ?Veiculo {
        return $this->veiculo;
    }

    public function calcularCustoMensalPrefeitura(float $custoFixoPorAluno): float {
        return count($this->alunos) * $custoFixoPorAluno;
    }

    public function getAlunos(): array {
        return $this->alunos;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function paraArray(): array {
        $alunosArray = [];
        foreach ($this->alunos as $aluno) {
            $alunosArray[] = $aluno->paraArray();
        }

        return [
            'nome' => $this->nome,
            'distanciaKm' => $this->distanciaKm,
            'totalAlunos' => count($this->alunos),
            'alunos' => $alunosArray,
            'veiculo' => $this->veiculo ? $this->veiculo->paraArray() : null 
        ];
    }
}