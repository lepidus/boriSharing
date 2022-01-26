# Plugin de Compartilhamento Bori


## Compatibilidade

A última versão desse plugin é compatível com as seguintes aplicações da PKP:

* OJS 3.3.0

## Download do Plugin

Para baixar este plugin, vá para a [Página de versões](https://github.com/lepidus/boriSharing/releases) e baixe o pacote tar.gz da última versão compatível com o seu website.

## Instalação

1. Acesse a área administrativa do seu website OJS através do __Painel de Controle__.
2. Navege até `Configurações`>` Website`> `Plugins`> `Enviar novo plugin`.
3. Em __Enviar arquivo__ selecione o arquivo __boriSharing.tar.gz__, obtido da versão escolhida.
4. Clique em  __Salvar__ e o plugin estará instalado no seu website.

## Instruções de uso

Após a instalação, é necessário habilitar o plugin. Isso é feito em `Configurações do Website` > `Plugins` > `Plugins instalados`.

Com o plugin habilitado, deve-se expandir as suas opções clicando na seta ao lado do seu nome e então, acessar as suas `Configurações`. Na janela que abrir, estarão exibidos os _Termos de Privacidade_ e a _Chave de Autenticação_ do plugin. Após a leitura, você pode aceitar os termos, preencher o campo com a chave e confirmar a ação clicando em `Salvar`. O plugin só entrará em funcionamento mediante a aceitação dos Termos de Privacidade e o preenchimento da Chave de Autenticação.

Após aceitar os Termos de Privacidade, será enviado um e-mail para a Agência Bori, informando da ativação do plugin no seu website. A partir de então, sempre que um artigo for aceito no estágio de _Avaliação_, um e-mail com os dados desse será enviado à agência. O mesmo ocorrerá quando o artigo for publicado.

Para o funcionamento correto do plugin, é necessário configurar um Contato Principal para a revista, que será posto como remetente dos e-mails enviados. Isso pode ser feito em `Configurações` > `Revista` > `Contato`.

### Instalação para desenvolvimento

Quando instalado em ambiente de desenvolvimento, é necessário executar o comando `composer install` no diretório do plugin, para que os pacotes necessários para a execução dos testes sejam instalados.

Também é necessário executar o comando `php tools/upgrade.php upgrade` no diretório do OJS em que o plugin está instalado. Assim, os parâmetros do arquivo _settings.xml_ serão adicionados ao banco de dados.

## Licença
__Esse plugin é distribuído sob a licença GNU General Public License v3.0__

__Copyright (c) 2021 Agência Bori__

__Desenvolvido por Lepidus Tecnologia__