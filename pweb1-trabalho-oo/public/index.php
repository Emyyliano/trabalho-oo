<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/Aluno.php';
require_once __DIR__ . '/../src/Rota.php';
require_once __DIR__ . '/../src/Veiculo.php';
require_once __DIR__ . '/../src/Van.php';
require_once __DIR__ . '/../src/Onibus.php';

$arquivoDados = __DIR__ . '/dados.json';
$mensagem = '';
$erro = false;
$frota = []; // Array para guardar os objetos Veiculo

// Carregar dados existentes
if (file_exists($arquivoDados)) {
    $conteudo = file_get_contents($arquivoDados);
    $dadosSalvos = json_decode($conteudo, true);
    
    // Carregar Rota e Alunos
    $rotaNome = $dadosSalvos['rota']['nome'] ?? "Zona Rural - Escola Central";
    $rotaDist = $dadosSalvos['rota']['distanciaKm'] ?? 12.5;
    $rota = new Rota($rotaNome, (float)$rotaDist);
    
    $alunosSalvos = $dadosSalvos['rota']['alunos'] ?? $dadosSalvos['alunos'] ?? [];
    foreach ($alunosSalvos as $alunoArray) {
        $rota->adicionarAluno(new Aluno($alunoArray['nome'], $alunoArray['matricula']));
    }

    // Carregar Frota de Veículos
    $frotaSalva = $dadosSalvos['frota'] ?? [];
    foreach ($frotaSalva as $v) {
        $acessivel = $v['acessivel'] ?? false; // Carrega o dado salvo
        if ($v['tipo'] === 'Van') {
            $frota[] = new Van($v['placa'], $v['capacidade'], $acessivel, $v['portaAutomatica']);
        } elseif ($v['tipo'] === 'Onibus') {
            $frota[] = new Onibus($v['placa'], $v['capacidade'], $acessivel, $v['numeroEixos']);
        }
    }
} else {
    $rota = new Rota("Zona Rural - Escola Central", 12.5);
}

// Processar formulários POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    try {
        if ($acao === 'aluno') {
            $nome = $_POST['nome'] ?? '';
            $matricula = $_POST['matricula'] ?? '';
            $rota->adicionarAluno(new Aluno($nome, $matricula));
            $mensagem = "Aluno cadastrado com sucesso!";
        } elseif ($acao === 'veiculo') {
            $tipo = $_POST['tipo'] ?? '';
            $placa = $_POST['placa'] ?? '';
            $capacidade = (int)($_POST['capacidade'] ?? 0);
            $acessivel = isset($_POST['acessivel']) && $_POST['acessivel'] === 'sim'; // Captura o checkbox/select
            
            if ($tipo === 'Van') {
                $porta = isset($_POST['portaAutomatica']) && $_POST['portaAutomatica'] === 'sim';
                $frota[] = new Van($placa, $capacidade, $acessivel, $porta);
            } elseif ($tipo === 'Onibus') {
                $eixos = (int)($_POST['numeroEixos'] ?? 2);
                $frota[] = new Onibus($placa, $capacidade, $acessivel, $eixos);
            }
            $mensagem = "Veículo cadastrado com sucesso!";
        }
        // Salvar tudo no JSON
        $frotaArray = array_map(fn($v) => $v->paraArray(), $frota);
        $dadosParaSalvar = [
            'rota' => $rota->paraArray(),
            'frota' => $frotaArray
        ];
        file_put_contents($arquivoDados, json_encode($dadosParaSalvar, JSON_PRETTY_PRINT));
        
    } catch (Exception $e) {
        $erro = true;
        $mensagem = $e->getMessage(); 
    }
}

// Rota JSON
if (isset($_GET['formato']) && $_GET['formato'] === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    $frotaArray = array_map(fn($v) => $v->paraArray(), $frota);
    echo json_encode(['rota' => $rota->paraArray(), 'frota' => $frotaArray]);
    exit;
}

// Controle de Páginas
$pagina = $_GET['pagina'] ?? 'home';
$paginas_permitidas = ['home', 'cadastro', 'veiculos'];

if (!in_array($pagina, $paginas_permitidas)) {
    http_response_code(404);
    $pagina = '404';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUTE - Transporte Escolar</title>
    <link rel="stylesheet" href="estilo.css">
    <script>
        function alterarCamposVeiculo() {
            const tipo = document.getElementById('tipoVeiculo').value;
            document.getElementById('campoVan').style.display = (tipo === 'Van') ? 'block' : 'none';
            document.getElementById('campoOnibus').style.display = (tipo === 'Onibus') ? 'block' : 'none';
        }
    </script>
</head>
<body>

<nav>
    <a href="?pagina=home">Início (Rota)</a>
    <a href="?pagina=veiculos">Gerir Frota</a>
    <a href="?pagina=cadastro">Novo Aluno</a>
    <a href="?formato=json" target="_blank">Exportar JSON</a>
</nav>

<div class="container">
    <?php if ($mensagem): ?>
        <div class="<?= $erro ? 'erro' : 'sucesso' ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <?php if ($pagina === 'home'): ?>
        <h2>Rota: <?= htmlspecialchars($rota->getNome()) ?></h2>
        <p><strong>Custo Mensal da Prefeitura:</strong> R$ <?= number_format($rota->calcularCustoMensalPrefeitura(85.00), 2, ',', '.') ?></p>
        <br>
        <h3>Alunos Cadastrados (<?= count($rota->getAlunos()) ?>)</h3>
        <ul>
            <?php foreach ($rota->getAlunos() as $aluno): ?>
                <li>
                    <strong><?= htmlspecialchars($aluno->getNome()) ?></strong> 
                    <span>Matrícula: <?= htmlspecialchars($aluno->getMatricula()) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (count($rota->getAlunos()) === 0): ?>
            <p>Nenhum aluno cadastrado.</p>
        <?php endif; ?>

    <?php elseif ($pagina === 'cadastro'): ?>
        <h2>Cadastrar Aluno no Transporte</h2>
        <form method="POST" action="?pagina=cadastro">
            <input type="hidden" name="acao" value="aluno">
            <div>
                <label>Nome do Aluno:</label>
                <input type="text" name="nome" required>
            </div>
            <div>
                <label>Matrícula:</label>
                <input type="text" name="matricula" required>
            </div>
            <button type="submit">Cadastrar Aluno</button>
        </form>

    <?php elseif ($pagina === 'veiculos'): ?>
        <h2>Gestão de Veículos</h2>
        
        <form method="POST" action="?pagina=veiculos" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #eee;">
            <input type="hidden" name="acao" value="veiculo">
            
            <div>
                <label>Tipo de Veículo:</label>
                <select name="tipo" id="tipoVeiculo" onchange="alterarCamposVeiculo()" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; margin-top: 5px;">
                    <option value="Van">Van Escolar</option>
                    <option value="Onibus">Ônibus Escolar</option>
                </select>
            </div>
            <div>
                <label>Acessível para Mobilidade Reduzida?</label>
                <select name="acessivel" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; margin-top: 5px;">
                    <option value="nao">Não</option>
                    <option value="sim">Sim (Elevador)</option>
                </select>
            </div>
            <div>
                <label>Placa:</label>
                <input type="text" name="placa" required>
            </div>
            <div>
                <label>Capacidade (Lugares):</label>
                <input type="number" name="capacidade" min="1" required>
            </div>
            
            <!-- Campo específico da Van -->
            <div id="campoVan">
                <label>Porta Automática?</label>
                <select name="portaAutomatica" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; margin-top: 5px;">
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>
            </div>
            
            <!-- Campo específico do Ônibus -->
            <div id="campoOnibus" style="display: none;">
                <label>Número de Eixos:</label>
                <input type="number" name="numeroEixos" min="2" max="6" value="2">
            </div>
            
            <button type="submit">Cadastrar Veículo</button>
        </form>

        <h3>Frota Ativa (Polimorfismo Visual)</h3>
        <p style="margin-bottom: 15px; font-size: 0.9em; color: #666;">O custo de manutenção baseia-se num percurso padrão de 100km. Van e Ônibus calculam de forma diferente.</p>
        <ul>
            <?php foreach ($frota as $veiculo): ?>
                <li>
                    <strong><?= get_class($veiculo) ?> - <?= htmlspecialchars($veiculo->getPlaca()) ?></strong>
                    <span>Capacidade: <?= $veiculo->getCapacidade() ?> lugares</span>
                    <span style="color: #d32f2f; font-weight: bold;">
                        Custo de Manutenção (100km): R$ <?= number_format($veiculo->calcularCustoManutencao(100.0), 2, ',', '.') ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (empty($frota)): ?>
            <p>Nenhum veículo registado na frota.</p>
        <?php endif; ?>

    <?php elseif ($pagina === '404'): ?>
        <h2>Erro 404</h2>
        <p>A página que você tentou acessar não existe no sistema.</p>
    <?php endif; ?>
</div>

</body>
</html>