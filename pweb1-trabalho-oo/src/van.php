<?php
declare(strict_types=1);
require_once __DIR__ . '/Veiculo.php';

class Van extends Veiculo {
    private bool $portaAutomatica;

    public function __construct(string $placa, int $capacidade, bool $acessivel, bool $portaAutomatica) {
        // Chamada ao construtor da classe mãe (OO2)
        parent::__construct($placa, $capacidade, $acessivel);
        $this->portaAutomatica = $portaAutomatica;
    }

    // Sobrescrita do método respondendo de forma específica (OO2, OO3)
    public function calcularCustoManutencao(float $quilometros): float {
        $custoBase = parent::calcularCustoManutencao($quilometros);
        $taxaPorta = $this->portaAutomatica ? 50.00 : 0.00;
        return $custoBase * 0.8 + $taxaPorta; // Van é mais económica, mas tem taxa de porta
    }

    public function paraArray(): array {
        $dados = parent::paraArray();
        $dados['tipo'] = 'Van';
        $dados['portaAutomatica'] = $this->portaAutomatica;
        return $dados;
    }
}