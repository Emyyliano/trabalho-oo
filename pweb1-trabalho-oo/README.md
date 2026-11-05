# SUTE ( SISTEMA ÚNICO DE TRANSPORTE ESCOLAR )

> Trabalho individual de Programação Web I (IFCE Campus Boa Viagem): sistema web orientado a objetos em PHP.

**Autor(a):** Emyliano Carvalho Costa
**Tema:** 14 - Transporte Escolar
**Vídeo:** _(cole aqui o link do vídeo)_

## O problema

O SUTE (Sistema Único de Transportes Escolares) resolve o desafio do controle de passageiros diários e alocação de frota para o transporte público escolar municipal. Ele auxilia gestores públicos (prefeituras) e empresas terceirizadas a gerenciar rotas, vincular veículos (vans e ônibus) aos trajetos e calcular de forma automatizada os custos de repasse financeiro baseados na lotação dos estudantes.

## Como executar

Requisitos: PHP 8.3 ou superior.

```bash
git clone [https://github.com/Emyyliano/pweb1-trabalho-oo.git](https://github.com/Emyyliano/pweb1-trabalho-oo.git)
cd pweb1-trabalho-oo
php -S localhost:8000 -t public -d display_errors=1 -d output_buffering=0
Abra http://localhost:8000

Para rodar o roteiro de testes no terminal:

```
php teste.php
```

## Telas

_(Coloque 2 ou 3 prints na pasta `docs/` e mostre aqui.)_

## Rotas

| URL | O que mostra | Método | Status |
|---|---|---|---|
| `/` | Redireciona para o Painel Geral (Home) | GET | 200 |
| `/?pagina=...` | Telas de navegação (home, cadastro, veiculos, rotas) | GET | 200 |
| `/?pagina=inexistente` | página em branco | GET | 404 |
| `/?formato=json` | Estrutura completa de rotas e frota serializada em JSON | GET | 200 |
| `/?pagina=...` (formulário) | Processamento de entidades (criar, mover ou excluir) | POST | |

## Regras de negócio

| Nº | Regra | Classe e método | Mensagem da exceção |
|---|---|---|---|
| 1 | O nome do aluno não pode conter números | Aluno::setNome() | "Erro: O nome do aluno não pode conter números." |
| 2 | A matrícula deve conter apenas números | Aluno::setMatricula() | "Erro: A matrícula deve conter apenas números." |
| 3 | A placa do veículo deve seguir o padrão DETRAN | Veiculo::__construct() | "Erro: A placa deve seguir o formato DETRAN (Ex: ABC-1234 ou ABC1D23)." |

## Documentação

- [Modelagem (diagrama de classes)](docs/modelagem.md)
- [Testes](docs/testes.md)
- [Diário de uso de LLM](docs/llm.md)
- [Relatório (PDF)](docs/relatorio.pdf)

## Ferramentas utilizadas

| Ferramenta | Para que usei |
|---|---|
| PHP 8.3 | Desenvolvimento da lógica de backend, modelagem orientada a objetos e rotas |
| VS Code | Edição de código, manipulação dos arquivos JSON locais e terminal |
| Git e GitHub | Versionamento de código local e hospedagem do repositório para entrega |
| curl | Depuração do cabeçalho HTTP e teste visualização crua da rota ?formato=json |
| Navegador e DevTools | Testes de interface web, responsividade CSS e fluxos de formulários |
| LLM: _(qual?)_ | Assistente técnico para refatoração, regras Regex e preenchimento LaTeX |
| Gravador de tela: _(qual?)_ | |
