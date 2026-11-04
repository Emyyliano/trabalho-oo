<?php
declare(strict_types=1);

class Veiculo {
    protected string $placa;
    protected int $capacidade;
    protected bool $acessivel;

    public function __construct(string $placa, int $capacidade, bool $acessivel) {
        if ($capacidade <= 0 || $capacidade > 100) {
            throw new Exception("Erro: A capacidade do veículo deve ser entre 1 e 100 lugares.");
        }
        
        // Remove traços e espaços, e coloca tudo em maiúsculo para validar
        $placaFormatada = strtoupper(str_replace(['-', ' '], '', trim($placa)));
        
        // Regra para padrão Detran Brasil (Ex: ABC1234 ou ABC1D23)
        if (!preg_match('/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/', $placaFormatada)) {
            throw new Exception("Erro: A placa deve seguir o formato DETRAN (Ex: ABC-1234 ou ABC1D23).");
        }
        
        $this->placa = $placaFormatada;
        $this->capacidade = $capacidade;
        $this->acessivel = $acessivel;
    }

    public function getPlaca(): string { return $this->placa; }
    public function getCapacidade(): int { return $this->capacidade; }
    public function isAcessivel(): bool { return $this->acessivel; }

    public function calcularCustoManutencao(float $quilometros): float {
        $custo = $quilometros * 2.00;
        return $this->acessivel ? $custo + 40.00 : $custo; 
    }

    public function paraArray(): array {
        return [
            'placa' => $this->placa,
            'capacidade' => $this->capacidade,
            'acessivel' => $this->acessivel
        ];
    }
}