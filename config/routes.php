<?php

use Alura\Cursos\Controller\{
      Deslogar,
      Exclusao,
      FormularioEdicao,
      FormularioInsercao,
      FormularioLogin,
      ListarCursos,
      Persistecia,
      RealizarLogin
};

return $rotas = [
      '/listar-cursos' => ListarCursos::class,
      '/novo-curso' => FormularioInsercao::class,
      '/salvar-curso' => Persistecia::class,
      '/excluir-curso' => Exclusao::class,
      '/alterar-curso' => FormularioEdicao::class,
      '/login' => FormularioLogin::class,
      '/realiza-login' => RealizarLogin::class,
      '/logout' => Deslogar::class,
];

