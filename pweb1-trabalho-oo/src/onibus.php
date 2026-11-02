<?php
declare(strict_types=1);
require_once __DIR__ . '/Veiculo.php';

class Onibus extends Veiculo {
    private int $numeroEixos;

    public function __construct(string $placa, int $capacidade, int $numeroEixos) {
        parent::__construct($placa, $capacidade);
        $this->numeroEixos = $numeroEixos;
    }

    // Sobrescrita do método respondendo de forma específica (OO2, OO3)
    public function calcularCustoManutencao(float $quilometros): float {
        $custoBase = parent::calcularCustoManutencao($quilometros);
        return $custoBase * 1.5 + ($this->numeroEixos * 120.00); // Autocarro gasta mais e paga taxa por eixo
    }

    public function paraArray(): array {
        $dados = parent::paraArray();
        $dados['tipo'] = 'Onibus';
        $dados['numeroEixos'] = $this->numeroEixos;
        return $dados;
    }
}