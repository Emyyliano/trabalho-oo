<?php
declare(strict_types=1);

class Aluno {
    private string $nome;
    private string $matricula;

    public function __construct(string $nome, string $matricula) {
        $this->setNome($nome);
        $this->setMatricula($matricula); // Validação no construtor
    }

    public function getNome(): string { return $this->nome; }
    
    public function setNome(string $nome): void {
        $nomeLimpo = trim($nome);
        
        if (strlen($nomeLimpo) < 3) {
            throw new Exception("Erro: O nome do aluno deve ter pelo menos 3 caracteres.");
        }
        if (strlen($nomeLimpo) > 80) {
            throw new Exception("Erro: O nome do aluno não pode ultrapassar 80 caracteres.");
        }
        if (preg_match('/[0-9]/', $nomeLimpo)) {
            throw new Exception("Erro: O nome do aluno não pode conter números.");
        }
        
        $this->nome = $nomeLimpo;
    }

    public function getMatricula(): string { return $this->matricula; }

    public function setMatricula(string $matricula): void {
        $matriculaLimpa = trim($matricula);
        if (!ctype_digit($matriculaLimpa)) {
            throw new Exception("Erro: A matrícula deve conter apenas números.");
        }
        if (strlen($matriculaLimpa) > 20) {
            throw new Exception("Erro: A matrícula não pode ultrapassar 20 caracteres.");
        }
        $this->matricula = $matriculaLimpa;
    }

    public function paraArray(): array {
        return [
            'nome' => $this->nome,
            'matricula' => $this->matricula
        ];
    }
}