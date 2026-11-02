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

    // Método para adicionar um objeto à lista (OO6)
    public function adicionarAluno(Aluno $aluno): void {
        $this->alunos[] = $aluno;
    }

    // Método que calcula algo sobre a lista (OO6)
    public function calcularMensalidadeTotal(float $valorPorAluno): float {
        return count($this->alunos) * $valorPorAluno;
    }

    public function getAlunos(): array {
        return $this->alunos;
    }

    public function getNome(): string {
        return $this->nome;
    }

    // (OO7)
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