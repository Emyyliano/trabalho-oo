<?php
declare(strict_types=1);

class Veiculo {
    protected string $placa;
    protected int $capacidade;
    protected bool $acessivel; // Novo atributo

    public function __construct(string $placa, int $capacidade, bool $acessivel) {
        if ($capacidade <= 0) {
            throw new Exception("Erro: A capacidade do veículo deve ser maior que zero.");
        }
        $this->placa = $placa;
        $this->capacidade = $capacidade;
        $this->acessivel = $acessivel;
    }

    public function getPlaca(): string { return $this->placa; }
    public function getCapacidade(): int { return $this->capacidade; }
    public function isAcessivel(): bool { return $this->acessivel; } // Getter

    public function calcularCustoManutencao(float $quilometros): float {
        $custo = $quilometros * 2.00;
        // Veículos acessíveis têm um custo extra de manutenção do elevador/rampa
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