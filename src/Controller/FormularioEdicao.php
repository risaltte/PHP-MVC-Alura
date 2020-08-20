<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\RenderizadorDeHtmlTrait;
use Alura\Cursos\Infra\EntityManagerCreator;
use Doctrine\Common\Persistence\ObjectRepository;

class FormularioEdicao implements InterfaceControladorRequisicao
{
    use RenderizadorDeHtmlTrait;

    /** @var ObjectRepository */
    private ObjectRepository $repositorioCursos;

    public function __construct()
    {
        $entityManager = (new EntityManagerCreator())
            ->getEntityManager();
        $this->repositorioCursos = $entityManager
            ->getRepository(Curso::class);
    }

    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (is_null($id) || $id === false) {
            header('Location: /listar-cursos');
            return;
        }

        /** @var Curso $curso */
        $curso = $this->repositorioCursos->find($id);

       echo $this->renderizaHtml('cursos/formulario.php', [
            'curso' => $curso,
            'titulo' => 'Alterar curso ' . $curso->getDescricao(),
        ]);
    }
}
