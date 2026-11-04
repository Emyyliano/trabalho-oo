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
$frota = []; 
$rotas = []; 

if (file_exists($arquivoDados)) {
    $conteudo = file_get_contents($arquivoDados);
    $dadosSalvos = json_decode($conteudo, true);
    
    $frotaSalva = $dadosSalvos['frota'] ?? [];
    foreach ($frotaSalva as $v) {
        $acessivel = $v['acessivel'] ?? false; 
        if ($v['tipo'] === 'Van') {
            $frota[] = new Van($v['placa'], $v['capacidade'], $acessivel);
        } elseif ($v['tipo'] === 'Onibus') {
            $frota[] = new Onibus($v['placa'], $v['capacidade'], $acessivel, $v['numeroEixos']);
        }
    }

    $rotasSalvas = $dadosSalvos['rotas'] ?? [];
    foreach ($rotasSalvas as $r) {
        $novaRota = new Rota($r['nome'], (float)$r['distanciaKm']);
        foreach ($r['alunos'] as $alunoArray) {
            $novaRota->adicionarAluno(new Aluno($alunoArray['nome'], $alunoArray['matricula']));
        }
        $veiculoVinculado = $r['veiculo'] ?? null;
        if ($veiculoVinculado) {
            foreach ($frota as $veiculoFrota) {
                if ($veiculoFrota->getPlaca() === $veiculoVinculado['placa']) {
                    $novaRota->vincularVeiculo($veiculoFrota);
                    break;
                }
            }
        }
        $rotas[] = $novaRota;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    try {
        if ($acao === 'rota') {
            $nomeRota = $_POST['nomeRota'] ?? '';
            $distancia = (float)($_POST['distanciaKm'] ?? 0);
            $rotas[] = new Rota($nomeRota, $distancia);
            $mensagem = "Rota cadastrada com sucesso!";
            
        } elseif ($acao === 'aluno') {
            $indexRota = $_POST['indexRota'] ?? -1;
            if (!isset($rotas[$indexRota])) throw new Exception("Selecione uma rota válida.");
            $nome = $_POST['nome'] ?? '';
            $matricula = $_POST['matricula'] ?? '';
            $rotas[$indexRota]->adicionarAluno(new Aluno($nome, $matricula));
            $mensagem = "Aluno cadastrado na rota com sucesso!";
            
        } elseif ($acao === 'excluir_aluno') {
            $indexRota = $_POST['indexRota'] ?? -1;
            $matricula = $_POST['matricula'] ?? '';
            if (isset($rotas[$indexRota])) {
                $rotas[$indexRota]->removerAluno($matricula);
                $mensagem = "Aluno removido com sucesso!";
            }
            
        } elseif ($acao === 'mover_aluno') {
            $indexOrigem = $_POST['indexOrigem'] ?? -1;
            $indexDestino = $_POST['indexDestino'] ?? -1;
            $matricula = $_POST['matricula'] ?? '';
            if (isset($rotas[$indexOrigem]) && isset($rotas[$indexDestino])) {
                $aluno = $rotas[$indexOrigem]->removerAluno($matricula);
                if ($aluno) {
                    $rotas[$indexDestino]->adicionarAluno($aluno);
                    $mensagem = "Aluno movido de rota com sucesso!";
                }
            }

        } elseif ($acao === 'veiculo') {
            $tipo = $_POST['tipo'] ?? '';
            $placa = $_POST['placa'] ?? '';
            $capacidade = (int)($_POST['capacidade'] ?? 0);
            $acessivel = isset($_POST['acessivel']) && $_POST['acessivel'] === 'sim'; 
            
            if ($tipo === 'Van') {
                $frota[] = new Van($placa, $capacidade, $acessivel);
            } elseif ($tipo === 'Onibus') {
                $eixos = (int)($_POST['numeroEixos'] ?? 2);
                $frota[] = new Onibus($placa, $capacidade, $acessivel, $eixos);
            }
            $mensagem = "Veículo cadastrado com sucesso!";
            
        } elseif ($acao === 'excluir_veiculo') {
            $placa = $_POST['placa'] ?? '';
            foreach ($frota as $key => $v) {
                if ($v->getPlaca() === $placa) {
                    unset($frota[$key]);
                    $frota = array_values($frota);
                    break;
                }
            }
            foreach ($rotas as $r) {
                if ($r->getVeiculo() && $r->getVeiculo()->getPlaca() === $placa) {
                    $r->desvincularVeiculo();
                }
            }
            $mensagem = "Veículo removido da frota com sucesso!";

        } elseif ($acao === 'vincular') {
            $indexRota = $_POST['indexRota'] ?? -1;
            if (!isset($rotas[$indexRota])) throw new Exception("Selecione uma rota válida.");
            $placaSelecionada = $_POST['placa'] ?? '';
            
            // Remove o veículo de qualquer outra rota antes de vincular na nova (Garante a movimentação)
            foreach ($rotas as $r) {
                if ($r->getVeiculo() && $r->getVeiculo()->getPlaca() === $placaSelecionada) {
                    $r->desvincularVeiculo();
                }
            }
            foreach ($frota as $v) {
                if ($v->getPlaca() === $placaSelecionada) {
                    $rotas[$indexRota]->vincularVeiculo($v);
                    $mensagem = "Veículo movido/vinculado à rota com sucesso!";
                    break;
                }
            }
        }
        
        $frotaArray = array_map(fn($v) => $v->paraArray(), $frota);
        $rotasArray = array_map(fn($r) => $r->paraArray(), $rotas);
        $dadosParaSalvar = ['rotas' => $rotasArray, 'frota' => $frotaArray];
        file_put_contents($arquivoDados, json_encode($dadosParaSalvar, JSON_PRETTY_PRINT));
        
    } catch (Exception $e) {
        $erro = true;
        $mensagem = $e->getMessage(); 
    }
}

if (isset($_GET['formato']) && $_GET['formato'] === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    $frotaArray = array_map(fn($v) => $v->paraArray(), $frota);
    $rotasArray = array_map(fn($r) => $r->paraArray(), $rotas);
    echo json_encode(['rotas' => $rotasArray, 'frota' => $frotaArray]);
    exit;
}

$pagina = $_GET['pagina'] ?? 'home';
$paginas_permitidas = ['home', 'cadastro', 'veiculos', 'rotas'];

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
            document.getElementById('campoOnibus').style.display = (tipo === 'Onibus') ? 'block' : 'none';
        }
    </script>
</head>
<body>

<nav>
    <a href="?pagina=home">Painel Geral</a>
    <a href="?pagina=rotas">Gerir Rotas</a>
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
        <h2>Painel Geral - Rotas Ativas</h2>
        
        <?php if (empty($rotas)): ?>
            <p>Nenhuma rota cadastrada no sistema.</p>
        <?php endif; ?>

        <?php foreach ($rotas as $index => $rota): ?>
            <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-left: 5px solid var(--primary); margin-bottom: 30px;">
                <h2 style="margin-bottom: 5px;">Rota: <?= htmlspecialchars($rota->getNome()) ?></h2>
                <p><strong>Custo Mensal Prefeitura:</strong> R$ <?= number_format($rota->calcularCustoMensalPrefeitura(85.00), 2, ',', '.') ?></p>
                
                <div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin-top: 15px; margin-bottom: 15px;">
                    <h3 style="margin-top: 0;">Veículo Responsável</h3>
                    <?php if ($rota->getVeiculo()): ?>
                        <p><strong>Placa:</strong> <?= htmlspecialchars($rota->getVeiculo()->getPlaca()) ?> (<?= get_class($rota->getVeiculo()) ?>)</p>
                        <p><strong>Capacidade:</strong> <?= $rota->getVeiculo()->getCapacidade() ?> lugares | <strong>Acessível:</strong> <?= $rota->getVeiculo()->isAcessivel() ? 'Sim' : 'Não' ?></p>
                    <?php else: ?>
                        <p style="color: #d32f2f;">Nenhum veículo vinculado a esta rota.</p>
                    <?php endif; ?>

                    <?php if (!empty($frota)): ?>
                        <form method="POST" action="?pagina=home" style="margin-top: 10px; display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
                            <input type="hidden" name="acao" value="vincular">
                            <input type="hidden" name="indexRota" value="<?= $index ?>">
                            <select name="placa" required style="padding: 8px; border-radius: 5px; border: 1px solid #ddd; flex-grow: 1;">
                                <option value="">Alterar/Vincular veículo...</option>
                                <?php foreach ($frota as $v): ?>
                                    <option value="<?= htmlspecialchars($v->getPlaca()) ?>"><?= get_class($v) ?> - <?= htmlspecialchars($v->getPlaca()) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" style="padding: 8px 15px; margin: 0;">Vincular</button>
                        </form>
                    <?php endif; ?>
                </div>

                <h3>Alunos (<?= count($rota->getAlunos()) ?>)</h3>
                <?php if (count($rota->getAlunos()) === 0): ?>
                    <p style="color: #666;">Sem alunos nesta rota.</p>
                <?php else: ?>
                    <ul style="padding: 0;">
                        <?php foreach ($rota->getAlunos() as $aluno): ?>
                            <li style="margin-bottom: 10px; padding: 10px; background: #f8f9fa; border-radius: 5px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <div>
                                    <strong><?= htmlspecialchars($aluno->getNome()) ?></strong> - Matrícula: <?= htmlspecialchars($aluno->getMatricula()) ?>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <?php if (count($rotas) > 1): ?>
                                    <form method="POST" style="margin: 0;">
                                        <input type="hidden" name="acao" value="mover_aluno">
                                        <input type="hidden" name="indexOrigem" value="<?= $index ?>">
                                        <input type="hidden" name="matricula" value="<?= htmlspecialchars($aluno->getMatricula()) ?>">
                                        <select name="indexDestino" required style="padding: 5px; font-size: 0.9em; border-radius: 3px;">
                                            <option value="">Mover para rota...</option>
                                            <?php foreach ($rotas as $i => $rDestino): if ($i !== $index): ?>
                                                <option value="<?= $i ?>"><?= htmlspecialchars($rDestino->getNome()) ?></option>
                                            <?php endif; endforeach; ?>
                                        </select>
                                        <button type="submit" style="padding: 5px 10px; font-size: 0.9em; margin: 0;">Mover</button>
                                    </form>
                                    <?php endif; ?>
                                    <form method="POST" style="margin: 0;" onsubmit="return confirm('Excluir este aluno?');">
                                        <input type="hidden" name="acao" value="excluir_aluno">
                                        <input type="hidden" name="indexRota" value="<?= $index ?>">
                                        <input type="hidden" name="matricula" value="<?= htmlspecialchars($aluno->getMatricula()) ?>">
                                        <button type="submit" style="padding: 5px 10px; font-size: 0.9em; background: #d32f2f; margin: 0;">Excluir</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    <?php elseif ($pagina === 'rotas'): ?>
        <h2>Cadastrar Nova Rota</h2>
        <form method="POST" action="?pagina=rotas">
            <input type="hidden" name="acao" value="rota">
            <div>
                <label>Nome da Rota:</label>
                <input type="text" name="nomeRota" required>
            </div>
            <div>
                <label>Distância (Km):</label>
                <input type="number" step="0.1" name="distanciaKm" min="0.1" required>
            </div>
            <button type="submit">Criar Rota</button>
        </form>

    <?php elseif ($pagina === 'cadastro'): ?>
        <h2>Cadastrar Aluno</h2>
        <form method="POST" action="?pagina=cadastro">
            <input type="hidden" name="acao" value="aluno">
            <div>
                <label>Selecione a Rota:</label>
                <select name="indexRota" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; margin-top: 5px;">
                    <?php foreach ($rotas as $index => $r): ?>
                        <option value="<?= $index ?>"><?= htmlspecialchars($r->getNome()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Nome do Aluno:</label>
                <input type="text" name="nome" maxlength="80" pattern="[A-Za-zÀ-ÿ\s]+" title="Apenas letras são permitidas" required>
            </div>
            <div>
                <label>Matrícula (Apenas números):</label>
                <input type="text" name="matricula" maxlength="20" pattern="[0-9]+" title="Apenas números" required>
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
                <label>Placa (Ex: ABC-1234 ou ABC1D23):</label>
                <input type="text" name="placa" placeholder="ABC-1234" required>
            </div>
            <div>
                <label>Capacidade (Lugares):</label>
                <input type="number" name="capacidade" min="1" max="100" required>
            </div>
            <div id="campoOnibus" style="display: none;">
                <label>Número de Eixos:</label>
                <input type="number" name="numeroEixos" min="2" max="6" value="2">
            </div>
            <button type="submit">Cadastrar Veículo</button>
        </form>

        <h3>Frota Ativa</h3>
        <ul style="padding: 0;">
            <?php foreach ($frota as $veiculo): ?>
                <li style="margin-bottom: 10px; padding: 15px; background: #f8f9fa; border-left: 4px solid var(--primary); border-radius: 5px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <div>
                        <strong><?= get_class($veiculo) ?> - <?= htmlspecialchars($veiculo->getPlaca()) ?></strong><br>
                        <span>Capacidade: <?= $veiculo->getCapacidade() ?> lugares | Acessível: <?= $veiculo->isAcessivel() ? '♿ Sim' : 'Não' ?></span><br>
                        <span style="color: #d32f2f; font-weight: bold;">Custo de Manutenção (100km): R$ <?= number_format($veiculo->calcularCustoManutencao(100.0), 2, ',', '.') ?></span>
                    </div>
                    <form method="POST" style="margin: 0;" onsubmit="return confirm('Excluir este veículo? Ele será removido de qualquer rota ativa.');">
                        <input type="hidden" name="acao" value="excluir_veiculo">
                        <input type="hidden" name="placa" value="<?= htmlspecialchars($veiculo->getPlaca()) ?>">
                        <button type="submit" style="background: #d32f2f; padding: 8px 15px; margin: 0;">Excluir</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>