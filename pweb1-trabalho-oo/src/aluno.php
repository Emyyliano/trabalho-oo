<?php
declare(strict_types=1);

class Aluno {
    private string $nome;
    private string $matricula;

    public function __construct(string $nome, string $matricula) {
        // Usa o setter no construtor para garantir que a validação é aplicada na criação
        $this->setNome($nome);
        $this->matricula = $matricula;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        if (strlen(trim($nome)) < 3) {
            // Regra de negócio (OO5): Lança exceção se o nome for inválido
            throw new Exception("Erro: O nome do aluno deve ter pelo menos 3 caracteres.");
        }
        $this->nome = trim($nome);
    }

    public function getMatricula(): string {
        return $this->matricula;
    }

    // Método obrigatório para a rota JSON (OO7)
    public function paraArray(): array {
        return [
            'nome' => $this->nome,
            'matricula' => $this->matricula
        ];
    }
}