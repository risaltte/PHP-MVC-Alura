<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Persistecia implements RequestHandlerInterface
{
    use FlashMessageTrait;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        $descricao = filter_var(
            $parsedBody['descricao'],
            FILTER_SANITIZE_STRING
        );

        $curso = new Curso();
        $curso->setDescricao($descricao);

        $queryString = $request->getQueryParams();

        if (isset($queryString['id'])){
            $id = filter_var(
                $queryString['id'],
                FILTER_VALIDATE_INT
            );
        } else {
            $id = null;
        }


        if (!is_null($id) && $id !== false) {               // para alterar curso
            $curso->setId($id);
            $this->entityManager->merge($curso);
            $this->defineMensagem('success', 'Curso atualizado com sucesso');
        } else {
            $this->entityManager->persist($curso);
            $this->defineMensagem('success', 'Curso inserido com sucesso');
        }

        $this->entityManager->flush();

        return new Response(302, ['Location' => '/listar-cursos']);

    }

}

