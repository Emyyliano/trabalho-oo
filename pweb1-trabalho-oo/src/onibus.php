<?php
declare(strict_types=1);
require_once __DIR__ . '/Veiculo.php';

class Onibus extends Veiculo {
    private int $numeroEixos;

    // Construtor atualizado para receber o $acessivel como 3º argumento
    public function __construct(string $placa, int $capacidade, bool $acessivel, int $numeroEixos) {
        parent::__construct($placa, $capacidade, $acessivel);
        $this->numeroEixos = $numeroEixos;
    }

    public function calcularCustoManutencao(float $quilometros): float {
        $custoBase = parent::calcularCustoManutencao($quilometros);
        return $custoBase * 1.5 + ($this->numeroEixos * 120.00); 
    }

    public function paraArray(): array {
        $dados = parent::paraArray();
        $dados['tipo'] = 'Onibus';
        $dados['numeroEixos'] = $this->numeroEixos;
        return $dados;
    }
}