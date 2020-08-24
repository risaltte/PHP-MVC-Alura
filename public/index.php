<?php

require __DIR__ . '/../vendor/autoload.php';

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

$caminho = $_SERVER['PATH_INFO'];                           // pega o caminho/recurso a partir da requisição
$rotas = require __DIR__ . '/../config/routes.php';         // novas rotas devem ser adicionadas nesse arquivo

if (!array_key_exists($caminho, $rotas)) {
    http_response_code(404);
    exit();
}

//inicializar sessão. Deve estar antes de qualquer código que envia saída para o navegador.
session_start();

// Verifica se usuário não está logado, e se a página não é rota de login. Redireciona para fazer login
$ehRotaDeLogin = stripos($caminho, 'login');
if (!isset($_SESSION['logado']) && $ehRotaDeLogin === false) {
    header('Location: /login');
    exit();
}

// criar fabrica (implementação das interfaces da PSR17)
$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(            // criador de request (Requisição)
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();               // montar a requisição

$classeControladora = $rotas[$caminho];

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/dependencies.php';       // contaner injection dependencie

/** @var RequestHandlerInterface $controlador */
$controlador = $container->get($classeControladora);              // instanciar objeto e suas dependencias a partir do container
$resposta = $controlador->handle($serverRequest);

foreach ($resposta->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}

echo $resposta->getBody();
