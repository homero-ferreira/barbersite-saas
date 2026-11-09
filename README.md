# BarberSite — Plataforma SaaS

**Desenvolvido por Homero Ferreira**

## Sobre o projeto

O BarberSite é uma plataforma SaaS desenvolvida em PHP e Laravel para criação e gerenciamento automatizado de sites para múltiplos clientes.

O projeto foi desenvolvido inicialmente para barbearias, mas sua arquitetura permite adaptação para diversos segmentos, como estética, seguros e prestação de serviços.

## Arquitetura

A aplicação utiliza uma arquitetura multi-tenant, permitindo que vários clientes utilizem uma única aplicação central.

Cada cliente possui:
- Domínio próprio.
- Logotipo e identidade visual personalizados.
- Cores e configurações individuais.
- Produtos, imagens e banners próprios.
- Site independente dos demais clientes.

O sistema identifica automaticamente o domínio acessado e apresenta o conteúdo correspondente ao cliente cadastrado.

## Funcionamento

1. Cadastro do cliente no painel administrativo.
2. Associação do domínio e configurações individuais.
3. Disponibilização automática do site.

Tempo estimado de implantação: aproximadamente 5 minutos por site.

## Tecnologias utilizadas

- PHP 8.3
- Laravel
- MySQL
- HTML5
- CSS3
- JavaScript
- Composer

## Demonstração online

- [Site Demo 1](https://demo1.homeroricardo.com.br)
- [Site Demo 2](https://demo2.homeroricardo.com.br)
- [Painel administrativo](https://homeroricardo.com.br/painel/login)

**Credenciais de demonstração:**

- Usuário: `demo`
- Senha: `demo`

## Ambiente de demonstração

Esta versão foi intencionalmente simplificada, com recursos extras desativados para concentrar a avaliação no cadastro de clientes, associação de domínios e publicação automática dos sites.

## Execução local

Para executar o projeto localmente, é necessário configurar o PHP, Composer, MySQL e as dependências do projeto.

Consulte o arquivo `.env.example` para configurar o ambiente e as credenciais do banco de dados.
