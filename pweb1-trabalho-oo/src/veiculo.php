<?php
declare(strict_types=1);

class Veiculo {
    protected string $placa;
    protected int $capacidade;

    public function __construct(string $placa, int $capacidade) {
        if ($capacidade <= 0) {
            // Regra de negócio (OO5)
            throw new Exception("Erro: A capacidade do veículo deve ser maior que zero.");
        }
        $this->placa = $placa;
        $this->capacidade = $capacidade;
    }

    public function getPlaca(): string {
        return $this->placa;
    }

    public function getCapacidade(): int {
        return $this->capacidade;
    }

    // Método que será sobrescrito nas classes filhas (OO2)
    public function calcularCustoManutencao(float $quilometros): float {
        return $quilometros * 2.00; 
    }

    public function paraArray(): array {
        return [
            'placa' => $this->placa,
            'capacidade' => $this->capacidade
        ];
    }
}