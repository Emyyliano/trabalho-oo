# Modelagem

## Diagrama de classes

Substitua o exemplo abaixo (tema 01, Feira Digital do Sertão) pelo diagrama do seu sistema.
Legenda: `<|--` herança ("é um"), `*--` composição ("tem um"), `#` protected, `-` private, `+` public.

```mermaid
classDiagram
    class Veiculo {
        #string placa
        #int capacidade
        #bool acessivel
        +__construct(string placa, int capacidade, bool acessivel)
        +getPlaca() string
        +getCapacidade() int
        +isAcessivel() bool
        +calcularCustoManutencao(float quilometros) float
        +paraArray() array
    }

    class Van {
        +__construct(string placa, int capacidade, bool acessivel)
        +calcularCustoManutencao(float quilometros) float
        +paraArray() array
    }

    class Onibus {
        -int numeroEixos
        +__construct(string placa, int capacidade, bool acessivel, int numeroEixos)
        +calcularCustoManutencao(float quilometros) float
        +paraArray() array
    }

    class Aluno {
        -string nome
        -string matricula
        +__construct(string nome, string matricula)
        +getNome() string
        +setNome(string nome) void
        +getMatricula() string
        +setMatricula(string matricula) void
        +paraArray() array
    }

    class Rota {
        -string nome
        -float distanciaKm
        -array alunos
        -Veiculo veiculo
        +__construct(string nome, float distanciaKm)
        +adicionarAluno(Aluno aluno) void
        +removerAluno(string matricula) Aluno
        +vincularVeiculo(Veiculo veiculo) void
        +desvincularVeiculo() void
        +getVeiculo() Veiculo
        +calcularCustoMensalPrefeitura(float custoFixoPorAluno) float
        +getAlunos() array
        +getNome() string
        +paraArray() array
    }

    Veiculo <|-- Van : herança
    Veiculo <|-- Onibus : herança
    Rota "1" *-- "*" Aluno : composição (tem muitos)
    Rota "0..*" o-- "0..1" Veiculo : agregação (vincula um)
```

## Justificativas

### Herança

A classe mãe é `Veiculo` e as classes filhas são `Van` e `Onibus`. A relação "é um" aplica-se perfeitamente, pois "Uma Van é um Veículo" e "Um Ônibus é um Veículo". Ambas herdam as características e validações básicas (placa, capacidade e acessibilidade), mas mantêm as suas particularidades. O método `calcularCustoManutencao()` foi sobrescrito em ambas as filhas porque cada tipo de transporte possui uma regra financeira distinta (a Van aplica um desconto sobre o valor base, enquanto o Ônibus acrescenta uma taxa extra multiplicada pelo número de eixos).

### Composição

A classe `Rota` guarda uma lista (array) de objetos da classe `Aluno`. A relação correta é "tem um" (ou "tem muitos") porque a `Rota` é constituída pelos estudantes alocados a ela ("A Rota tem alunos"). A relação não poderia ser "é um", pois um `Aluno` não é uma `Rota`; eles são conceitos totalmente distintos onde o objeto Aluno passa a existir como uma parte dentro do ciclo de vida e dos cálculos da Rota.

### Encapsulamento

**Têm Getter e Setter:** Os atributos da classe `Aluno` (`nome` e `matricula`) possuem tanto getters para leitura quanto setters altamente restritivos. Os setters bloqueiam números no nome, exigem que a matrícula tenha apenas dígitos e limitam o número de caracteres.

**Apenas Getter (sem Setter)**: Atributos da classe `Veiculo` (`placa`, `capacidade`, `acessivel`, `numeroEixos`) e da classe Rota (nome, distanciaKm) possuem apenas getters. Eles não têm setters de propósito: essas características estruturais devem ser definidas e validadas exclusivamente no momento da criação do objeto (no construtor) e não devem ser alteradas no meio da execução, garantindo a integridade dos dados na base JSON.
