<?php
require_once __DIR__ . '/src/Aluno.php';
require_once __DIR__ . '/src/Veiculo.php';
require_once __DIR__ . '/src/Van.php';
require_once __DIR__ . '/src/Onibus.php';
require_once __DIR__ . '/src/Rota.php';

echo "--- TESTES DO SISTEMA DE TRANSPORTE ESCOLAR ---\n\n";

// 1. Testando a Regra de Negócio com try/catch (OO5)
echo "Teste 1: Tentar criar um aluno com nome inválido\n";
try {
    $alunoInvalido = new Aluno("A", "2026001");
    echo "ERRO: O sistema aceitou um nome com menos de 3 caracteres.\n";
} catch (Exception $e) {
    echo "OK, recusado: " . $e->getMessage() . "\n";
}

// 2. Testando a Composição (OO6)
echo "\nTeste 2: Adicionar alunos à Rota e calcular custo da Prefeitura\n";
$rota = new Rota("Centro - Campus IFCE Boa Viagem", 12.5);
$rota->adicionarAluno(new Aluno("Maria Silva", "2026002"));
$rota->adicionarAluno(new Aluno("João Pedro", "2026003"));
echo "Total de alunos na rota: " . count($rota->getAlunos()) . "\n";
// Exemplo: a prefeitura gasta R$ 85,00 por aluno para manter esta rota
echo "Custo mensal para a prefeitura: R$ " . $rota->calcularCustoMensalPrefeitura(85.00) . "\n";

// 3. Testando o Polimorfismo (OO3)
echo "\nTeste 3: Polimorfismo no cálculo de manutenção (100km)\n";
$frota = [
    new Van("ABC-1234", 15, true),
    new Onibus("XYZ-9876", 45, 3)
];

// O mesmo método é chamado, mas cada classe filha responde da sua maneira
foreach ($frota as $veiculo) {
    $custo = $veiculo->calcularCustoManutencao(100.0);
    echo "Veículo " . $veiculo->getPlaca() . " (" . get_class($veiculo) . "): R$ " . number_format($custo, 2, ',', '.') . "\n";
}