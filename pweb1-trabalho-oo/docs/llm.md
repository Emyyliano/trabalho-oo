# Diário de uso de LLM

Registre pelo menos 5 interações relevantes. Em pelo menos uma, mostre um erro ou uma sugestão ruim da LLM que você percebeu e corrigiu.

## Interação 1

- **Ferramenta e data:**

`Google Gemini`

`01/11/2026`

- **Prompt:**

```
quero criar um sistema de transporte escolar
```

- **Resumo da resposta:**

A IA sugeriu uma estrutura inicial orientada a objetos, mas focou num modelo de negócio de transporte privado, cobrando mensalidades individuais de cada aluno.

- **O que aceitei, modifiquei ou rejeitei, e por quê:**

Rejeitei a ideia de "mensalidade". Modifiquei a regra de negócio e instruí a IA a focar num transporte escolar público fornecido pela Prefeitura, onde o cálculo financeiro seria baseado no custo de repasse por lotação da rota e na manutenção da frota.


## Interação 2

- **Ferramenta e data:**

`Google Gemini`

`02/11/2026`

- **Prompt:**

```
Preciso salvar os dados do formulário sem usar banco de dados MySQL, apenas com arquivos.
```

- **Resumo da resposta:**

A IA sugeriu utilizar as funções `json_encode`, `json_decode`, `file_get_contents` e `file_put_contents` do PHP para salvar os arrays de objetos no arquivo local dados.json.

- **O que aceitei, modifiquei ou rejeitei, e por quê:**

Aceitei a abordagem, pois atende perfeitamente ao requisito de persistência simples do trabalho. Modifiquei apenas a lógica de hidratação para garantir que os arrays carregados do JSON voltassem a ser objetos reais das classes Van, Onibus e Aluno no momento de exibir na tela.

## Interação 3

- **Ferramenta e data:**

`Google Gemini`

`02/11/2026`

- **Prompt:**

```
eu quero também poder excluir os veiculos, alunos. Mover os alunos pra outras rotas e os veiculos para outras rotas
```

- **Resumo da resposta:**

A IA gerou novos métodos na classe Rota (`removerAluno` e `desvincularVeiculo`) e atualizou o Front Controller (`index.php`) para processar os envios de formulário de exclusão e movimentação dinâmica entre rotas.

- **O que aceitei, modifiquei ou rejeitei, e por quê:**

Aceitei o código sugerido, pois resolveu a necessidade sem quebrar a estrutura da Orientação a Objetos. A movimentação funcionou removendo a instância de um array e dando o push no array da outra rota.

## Interação 4

- **Ferramenta e data:**

`Google Gemini`

`03/11/2026`

- **Prompt:**

```
no programa eu consigo colocar números no nome do aluno
```

- **Resumo da resposta:**

A IA identificou a falha de validação e sugeriu adicionar a função (`preg_match('/[0-9]/'`, `$nome`) no método `setNome()` da classe `Aluno` para atirar uma exceção, além de adicionar um atributo `pattern` no HTML.

- **O que aceitei, modifiquei ou rejeitei, e por quê:**

Aceitei e implementei a solução nos dois ficheiros. Além disso, adicionei o teste dessa restrição no ficheiro `teste.php` para comprovar ao professor o bloqueio rigoroso no encapsulamento da classe.

## Interação 5

- **Ferramenta e data:**

`Google Gemini`

`03/11/2026`

- **Prompt:**

```
o meu latex ta assim por enquanto: \UseRawInputEncoding ... (código LaTeX incompleto)
```

- **Resumo da resposta:**

A IA analisou o meu código LaTeX, preencheu as seções pendentes com explicações sobre as regras de negócio, inseriu as saídas reais do terminal (`teste.php` e `curl.exe`) e ajustou a formatação do documento final.

- **O que aceitei, modifiquei ou rejeitei, e por quê:**

Aceitei o código LaTeX completo. O texto gerado traduziu perfeitamente o que foi implementado no código PHP. Fiz apenas o ajuste manual das tags de hiperligação para inserir o link correto do meu repositório e vídeo.

## O erro que a LLM cometeu

O erro ocorreu no momento em que adicionamos o parâmetro booleano de "acessibilidade" nos veículos (após o início do projeto). A IA atualizou o código do `index.php` para enviar 4 parâmetros na criação de um Ônibus (`placa`, `capacidade`, `acessivel`, `numeroEixos`), mas esqueceu de atualizar o ficheiro `src/Onibus.php`.

Como o construtor do Ônibus ainda esperava 3 parâmetros, o PHP tentou atribuir o valor booleano da acessibilidade (`true` ou `false`) ao atributo de eixos (que era do tipo `int`). Percebi o erro quando a aplicação "quebrou" exibindo um Fatal Error na tela com a mensagem `TypeError: Onibus::__construct(): Argument #3 must be of type int, true given.`

Para corrigir, informei o erro no prompt para a IA, que gerou a assinatura do construtor correta em `src/Onibus.php`, adicionando a variável `bool $acessivel` na posição certa. Outro erro da IA (que eu precisei resolver sozinho) foi não avisar que eu deveria apagar o arquivo antigo `dados.json` após adicionar regras mais rígidas, o que causava travamentos ao ler placas fora do padrão.
