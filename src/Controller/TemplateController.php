<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TemplateController extends AbstractController
{
    #[Route('/template', name: 'template_inicio')]
    public function index(): Response
    {
        return $this->render("template/index.html.twig");
    }

    #[Route('/template/parametros/{id}/{slug}', name: 'template_parametros', defaults: ['id' => 101, 'slug' => 'algosss'])]
    public function parametros(int $id, string $slug = "Algo"): Response
    {
        die("ID: {$id} | SLUG: {$slug}");
    }

    #[Route('/template/excepcion', name: 'template_excepcion')]
    public function excepcion(): Response
    {
       throw $this->createNotFoundException('Esta URL no está disponible');
    }

    #[Route('/template/trabajo', name: 'template_trabajo')]
    public function trabajo(): Response
    {
        $name ='Juan';
        $apellido = 'Perez';
        $paises = array(
            array(
                'nombre' => 'Chile',
                'id' => 1
            ),
            array(
                'nombre' => 'Argentina',
                'id' => 2
            ),
            array(
                'nombre' => 'Brasil',
                'id' => 3
            ),
            array(
                'nombre' => 'España',
                'id' => 4
            ),
            array(
                'nombre' => 'Estados Unidos',
                'id' => 5
            )
        );
        return $this->render("template/trabajo.html.twig", compact('name', 'apellido','paises'));
    }

    #[Route('/template/layout', name: 'template_layout')]
    public function layout(): Response
    {
        return $this->render("template/layout.html.twig");
    }
}
