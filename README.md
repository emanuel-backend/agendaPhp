# AgendaPHP

Sistema de agenda de contatos desenvolvido em PHP, com integração a banco de dados MySQL.

## Sobre o projeto

O AgendaPHP é um sistema desenvolvido para facilitar o cadastro e o gerenciamento de contatos.

O projeto permite realizar operações básicas de cadastro, consulta, alteração e exclusão de contatos, utilizando PHP e MySQL.

## Funcionalidades

* Cadastrar contatos
* Listar contatos cadastrados
* Consultar informações dos contatos
* Editar contatos
* Excluir contatos
* Armazenar os dados em banco de dados MySQL
* Utilizar páginas PHP para interação com o sistema

## Tecnologias utilizadas

* PHP
* MySQL
* HTML
* CSS
* Apache
* Git
* GitHub

## Estrutura do projeto

```text
agendaPhp/
├── paginas/
│   └── conteudo/
├── README.md
└── outros arquivos do projeto
```

A estrutura pode variar conforme o desenvolvimento e a organização das páginas do sistema.

## Banco de dados

O sistema utiliza o MySQL para armazenar os contatos.

A tabela principal utilizada pelo sistema é:

```text
tb_contatos
```

Entre os dados armazenados estão informações como:

* ID do contato
* Nome
* Telefone
* E-mail

## Como executar o projeto

### 1. Instale um servidor local

É necessário ter um ambiente com:

* Apache
* PHP
* MySQL

No Linux, o projeto pode ser colocado em:

```text
/var/www/html/
```

### 2. Clone o repositório

```bash
git clone https://github.com/emanuel-backend/agendaPhp.git
```

### 3. Entre na pasta do projeto

```bash
cd agendaPhp
```

### 4. Configure o banco de dados

Crie o banco de dados no MySQL e configure a conexão utilizada pelo projeto.

Depois, importe as tabelas necessárias para o funcionamento do sistema.

### 5. Inicie o Apache e o MySQL

Verifique se os serviços estão funcionando corretamente.

### 6. Acesse o projeto

Abra o navegador e acesse o endereço correspondente ao servidor local, por exemplo:

```text
http://localhost/agendaPhp
```

## Git e branches

A branch principal do projeto é:

```text
main
```

Para desenvolvimento, também pode ser utilizada a branch:

```text
desenvolvimento
```

Para visualizar as branches:

```bash
git branch
```

Para mudar para a branch de desenvolvimento:

```bash
git checkout desenvolvimento
```

## Atualizando o projeto

Depois de realizar alterações no código:

```bash
git add .
```

Depois faça o commit:

```bash
git commit -m "Descrição da alteração"
```

Por fim, envie as alterações para o GitHub:

```bash
git push
```

## Objetivo

O objetivo do projeto é desenvolver um sistema simples de gerenciamento de contatos utilizando tecnologias web e conceitos de programação, banco de dados e controle de versão.

## Autor

Emanuel

Projeto desenvolvido para fins de estudo e aprendizado em desenvolvimento web.
