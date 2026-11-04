# Testes

## Roteiro de testes no terminal

Comando: `php teste.php`

```
--- TESTES DO SISTEMA SUTE ---

Teste 1: Nome invalido (Regra OO5)
OK, recusado: Erro: O nome do aluno deve ter pelo menos 3 caracteres.

Teste: Nome com numeros (Regra OO5)
OK, recusado: Erro: O nome do aluno não pode conter números.

Teste 2: Matricula com letras (Regra OO5)
OK, recusado: Erro: A matrícula deve conter apenas números.

Teste 3: Placa fora do padrao (Regra OO5)
OK, recusado: Erro: A placa deve seguir o formato DETRAN (Ex: ABC-1234 ou ABC1D23).

Teste 4: Polimorfismo (Regra OO3)
Veiculo ABC1234 (Van): R$ 160,00
Veiculo XYZ9876 (Onibus): R$ 600,00

Teste 5: Composicao (Regra OO6)
Total de alunos na rota 'Zona Rural - Centro': 2
Custo mensal para a prefeitura: R$ 170,00
```

**Explicação de cada linha:**

**Teste 1: Nome invalido (Regra OO5)**

Regra testada: OO5 (Tratamento de exceções e encapsulamento).

O que comprova: Prova que o método setter (`setNome`) da classe `Aluno` bloqueia a criação do objeto se o texto tiver menos de 3 caracteres, lançando uma `Exception` para impedir dados incompletos.

**Teste 2: Nome com numeros (Regra OO5)** 

Regra testada: OO5 (Tratamento de exceções e encapsulamento).

O que comprova: Prova que o sistema utiliza uma validação interna (expressão regular) no `setter` para rejeitar nomes de alunos que contenham dígitos numéricos, garantindo a integridade do dado.

**Teste 3: Matricula com letras (Regra OO5)**

Regra testada: OO5 (Tratamento de exceções e encapsulamento).

O que comprova: Prova que o método `setMatricula()` impede o cadastro de matrículas com letras ou símbolos, lançando um erro caso a string não seja estritamente numérica.

**Teste 4: Placa fora do padrao (Regra OO5)**

Regra testada: OO5 (Validação em construtores).

O que comprova: Prova que a classe mãe `Veiculo` possui uma blindagem no seu método construtor. Se a placa informada não passar na validação de formato do DETRAN, o sistema recusa a instanciação do objeto imediatamente.

**Teste 5: Polimorfismo (Regra OO3)**

Regra testada: OO3 (Polimorfismo).

O que comprova: Prova que, ao iterar uma lista de veículos e chamar a mesma função (`calcularCustoManutencao`), o PHP invoca comportamentos diferentes. A Van calcula o seu valor com desconto (R$ 160,00), enquanto o Onibus aplica a sua taxa específica baseada no número de eixos (R$ 600,00), sem que o laço principal precise saber qual é o tipo exato do veículo.

**Teste 6: Composicao (Regra OO6)**

Regra testada: OO6 (Composição/Agregação).

O que comprova: Prova a relação de que a `Rota` "tem muitos" alunos. A classe `Rota` consegue armazenar instâncias de Aluno numa lista privada e utilizar essa lista (contando a quantidade de objetos) para processar o cálculo financeiro dinâmico (`calcularCustoMensalPrefeitura`) que resulta em R$ 170,00 (2 alunos x R$ 85,00).

## Camada web

**Uma página que existe:** `curl.exe -i "http://localhost:8000/?pagina=rotas"` (cole só até o início do HTML)

```
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 04 Nov 2026 01:25:30 GMT
Connection: close
X-Powered-By: PHP/8.5.11
Content-type: text/html; charset=UTF-8

<!DOCTYPE html>
```

**Uma página que não existe:** `curl.exe -i "http://localhost:8000/?pagina=inexistente"`

```
HTTP/1.1 404 Not Found
Host: localhost:8000
Date: Wed, 04 Nov 2026 02:13:12 GMT
Connection: close
X-Powered-By: PHP/8.5.11
Content-type: text/html; charset=UTF-8
```

**A rota JSON:** `curl.exe -i "http://localhost:8000/?formato=json"`

```
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 04 Nov 2026 02:15:35 GMT
Connection: close
X-Powered-By: PHP/8.5.11
Content-Type: application/json; charset=utf-8

{"rotas":[{"nome":"Centro - IFCE","distanciaKm":6,"totalAlunos":3,"alunos":[{"nome":"Fulano","matricula":"123456789"},{"nome":"Cicrano","matricula":"987654321"},{"nome":"Beltrano","matricula":"123456777"}],"veiculo":{"placa":"ABC0990","capacidade":60,"acessivel":false,"tipo":"Van"}},{"nome":"Rotat\u00f3ria - IFCE","distanciaKm":4,"totalAlunos":1,"alunos":[{"nome":"Emyliano","matricula":"200000000000"}],"veiculo":{"placa":"ABC0991","capacidade":70,"acessivel":true,"tipo":"Onibus","numeroEixos":2}}],"frota":[{"placa":"ABC0990","capacidade":60,"acessivel":false,"tipo":"Van"},{"placa":"ABC0990","capacidade":60,"acessivel":false,"tipo":"Van"},{"placa":"ABC0991","capacidade":70,"acessivel":true,"tipo":"Onibus","numeroEixos":2},{"placa":"ABC0991","capacidade":15,"acessivel":true,"tipo":"Van"},{"placa":"ABC0991","capacidade":60,"acessivel":true,"tipo":"Onibus","numeroEixos":2},{"placa":"CDF9999","capacidade":20,"acessivel":false,"tipo":"Onibus","numeroEixos":2}]}
```

**O formulário com um dado inválido:** print da página com a mensagem de recusa e o log do servidor.

```
[Tue Nov  3 23:29:32 2026] [::1]:55158 Accepted
[Tue Nov  3 23:29:32 2026] [::1]:55158 [200]: GET /?pagina=cadastro
[Tue Nov  3 23:29:32 2026] [::1]:55158 Closing
```
