<?php
require_once __DIR__ . '/src/Aluno.php';
require_once __DIR__ . '/src/Veiculo.php';
require_once __DIR__ . '/src/Van.php';
require_once __DIR__ . '/src/Onibus.php';
require_once __DIR__ . '/src/Rota.php';

echo "--- TESTES DO SISTEMA SUTE ---\n\n";

echo "Teste 1: Nome invalido (Regra OO5)\n";
try {
    $aluno1 = new Aluno("A", "12345");
} catch (Exception $e) {
    echo "OK, recusado: " . $e->getMessage() . "\n\n";
}

echo "Teste: Nome com numeros (Regra OO5)\n";
try {
    $alunoErro = new Aluno("Maria2 Silva", "202603");
} catch (Exception $e) {
    echo "OK, recusado: " . $e->getMessage() . "\n\n";
}

echo "Teste 2: Matricula com letras (Regra OO5)\n";
try {
    $aluno2 = new Aluno("Joao", "123A45");
} catch (Exception $e) {
    echo "OK, recusado: " . $e->getMessage() . "\n\n";
}

echo "Teste 3: Placa fora do padrao (Regra OO5)\n";
try {
    $vanErro = new Van("ABC-12345", 15, false);
} catch (Exception $e) {
    echo "OK, recusado: " . $e->getMessage() . "\n\n";
}

echo "Teste 4: Polimorfismo (Regra OO3)\n";
$frota = [
    new Van("ABC-1234", 15, false),
    new Onibus("XYZ-9876", 45, true, 2)
];
foreach ($frota as $veiculo) {
    $custo = $veiculo->calcularCustoManutencao(100.0);
    echo "Veiculo " . $veiculo->getPlaca() . " (" . get_class($veiculo) . "): R$ " . number_format($custo, 2, ',', '.') . "\n";
}

echo "\nTeste 5: Composicao (Regra OO6)\n";
$rota = new Rota("Zona Rural - Centro", 12.5);
$rota->adicionarAluno(new Aluno("Maria", "202601"));
$rota->adicionarAluno(new Aluno("Pedro", "202602"));
echo "Total de alunos na rota '" . $rota->getNome() . "': " . count($rota->getAlunos()) . "\n";
echo "Custo mensal para a prefeitura: R$ " . number_format($rota->calcularCustoMensalPrefeitura(85.00), 2, ',', '.') . "\n";