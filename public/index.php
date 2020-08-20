<?php

require __DIR__ . '/../vendor/autoload.php';

use Alura\Cursos\Controller\InterfaceControladorRequisicao;

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

$classeControladora = $rotas[$caminho];

/** @var InterfaceControladorRequisicao $controlador */
$controlador = new $classeControladora();              // instanciar objeto a partir de uma variável com nome da classe
$controlador->processaRequisicao();
