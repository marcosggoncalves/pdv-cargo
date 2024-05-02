## Iniciando a Aplicação

Para iniciar a aplicação, siga estes passos:

1. Abra um terminal.
2. Navegue até o diretório raiz do projeto.
3. Execute o seguinte comando para iniciar os contêineres Docker em segundo plano:

```
   docker-compose up -d
```

Isso iniciará todos os serviços necessários para a aplicação PDV Cargo.

## Parando a Aplicação

Para parar a aplicação e desligar os contêineres Docker, siga estes passos:

1. Abra um terminal.
2. Navegue até o diretório raiz do projeto.
3. Execute o seguinte comando:

```
   docker-compose down
```

Isso desligará todos os contêineres Docker relacionados à aplicação PDV Cargo.

## Executando Comandos Específicos

Para executar comandos específicos dentro do contêiner `pdv-cargo-backend-1`, siga estes passos:

1. Abra um terminal.
2. Execute o seguinte comando para acessar o terminal do contêiner `pdv-cargo-backend-1`:

```
   docker exec -it pdv-cargo-backend-1 /bin/bash
```

Isso abrirá um terminal dentro do contêiner.

3. Dentro do terminal do contêiner, execute os comandos desejados, como por exemplo:

```
   php artisan storage:link
   php artisan migrate
   php artisan db:seed
```

Isso executará os comandos PHP Artisan necessários dentro do contêiner.

## Login Usuário

- **E-mail:** marcoslopesg7@gmail.com
- **Senha:** teste@123

## Observações

Certifique-se de ter as dependências necessárias instaladas, como Docker e Docker Compose, antes de tentar iniciar a aplicação.