# Teste Longevo

__IMPORTANTE__:
Durante o desenvolvimento do projeto foi identificado que há um possível inconsistência na elaboração das especificações do mesmo, conforme explicado a seguir.

Uma vez que há um pedido, já há um cliente associado ao mesmo.
Logo, uma vez que se informa um pedido, já se sabe quais os dados do cliente, 
não fazendo sentido associar outro email ao pedido, pois isso poderá causar inconsistência das informações do chamado, como foi visto que o mesmo já se possui um cliente associado.

No entanto, para cumprir com os requisitos, o sistema permitirá cadastrar um novo email do cliente na tabela de chamados, mas autocompletará as informações do cliente para os casos onde o email do cliente será aproveitado.

Para executar o projeto deste repositório siga os procedimentos a seguir.

__OBS.:__ _Certificar-se ter PostgreSQL e os drivers do PostgreSQL configurados no php.ini._

## Criando o Banco de Dados

Antes de criar o banco de dados, verifique se já não existe o banco de dados "sac_longevo" criado.

Para modificar o nome do banco veja `app/configs/parameters.yml` e altere o valor de `database_name`.

Verique também se suas informações de conexão correspondem aos valores do mesmo arquivo acima.

Acesse a pasta do projeto e execute:

`php bin/console doctrine:database:create`

## Criando as Tabelas
Acesse a pasta do projeto e execute:

`php bin/console doctrine:migrations:migrate`

Será solicitado confirmação, escolha 'y' e pressione enter.

## Populando as Tabelas
Acesse a pasta do projeto e execute:

`php bin/console doctrine:fixtures:load`

## Verificando 

__OBS.:__ Verifique os números de pedidos gerado clicando em "Relatório".

