<?php
declare(strict_types=1);
require_once __DIR__ . '/Veiculo.php';

class Van extends Veiculo {
    
    public function __construct(string $placa, int $capacidade, bool $acessivel) {
        parent::__construct($placa, $capacidade, $acessivel);
    }

    public function calcularCustoManutencao(float $quilometros): float {
        $custoBase = parent::calcularCustoManutencao($quilometros);
        return $custoBase * 0.8; 
    }

    public function paraArray(): array {
        $dados = parent::paraArray();
        $dados['tipo'] = 'Van';
        return $dados;
    }
}