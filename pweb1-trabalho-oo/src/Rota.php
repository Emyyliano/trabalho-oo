<?php
declare(strict_types=1);
require_once __DIR__ . '/Aluno.php';

class Rota {
    private string $nome;
    private float $distanciaKm;
    private array $alunos = []; // Composição: Rota tem alunos (OO6)

    public function __construct(string $nome, float $distanciaKm) {
        if ($distanciaKm <= 0) {
            // Regra de negócio (OO5)
            throw new Exception("Erro: A distância da rota deve ser maior que zero.");
        }
        $this->nome = $nome;
        $this->distanciaKm = $distanciaKm;
    }

    public function adicionarAluno(Aluno $aluno): void {
        $this->alunos[] = $aluno;
    }

    // Método alterado (OO6): Calcula o repasse/custo da prefeitura baseado no número de alunos
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
            'alunos' => $alunosArray
        ];
    }
}