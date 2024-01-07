<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Address;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address as MimeAddress;
use Symfony\Component\Mime\Email;

//HTTP Client
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Form\CategoriaApiType;
use Symfony\Component\HttpFoundation\Request;

//Filesystem
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class UtilidadesController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    #[Route('/utilidades', name: 'utilidades_inicio')]
    public function index(): Response
    {
        return $this->render('utilidades/index.html.twig');
    }

    #[Route('/utilidades/enviar-email', name: 'utilidades_email')]
    public function enviar_email(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from(new MimeAddress('from@example.com', 'Juan Pérez'))
            ->to('to@example.com', 'Mailtrap Inbox')
            ->subject('Mail de prueba')
            // ->text('Texto del mail')
            ->html('<p>Contenido mail <strong>con negritas</strong></p>');

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            die($e);
        }
        return $this->render('utilidades/enviar_email.html.twig');
    }

    #[Route('/utilidades/api-rest', name: 'utilidades_api_rest')]
    public function api_rest(): Response
    {

        $response = $this->client->request(
            'GET',
            'https://www.api.tamila.cl/api/categorias',
            [
                'headers' => [
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MzYsImlhdCI6MTcwNDIyMDI0MywiZXhwIjoxNzA2ODEyMjQzfQ.9tChYNr3pH_Xtr5-c7XJTvjMMhuMy7ClHLvM5kabuC0'
                ]
            ]
        );
        $statusCode = $response->getStatusCode();


        return $this->render('utilidades/api_rest.html.twig', compact('response'));
    }

    #[Route('/utilidades/api-rest/crear', name: 'utilidades_api_rest_crear')]
    public function api_rest_crear(Request $request): Response
    {

        $form = $this->createForm(CategoriaApiType::class, null);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');

        if ($form->isSubmitted()) {
            if ($this->isCsrfTokenValid('generico', $submittedToken)) {
                $campos = $form->getData();
                $response = $this->client->request(
                    'POST',
                    'https://www.api.tamila.cl/api/categorias',
                    [
                        'json' => ['nombre' => $campos['nombre']],
                        'headers' => [
                            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MzYsImlhdCI6MTcwNDIyMDI0MywiZXhwIjoxNzA2ODEyMjQzfQ.9tChYNr3pH_Xtr5-c7XJTvjMMhuMy7ClHLvM5kabuC0'
                        ]
                    ]
                );
                $this->addFlash('css', 'success');
                $this->addFlash('mensaje', 'Se creó el registro exitosamente');
                return $this->redirectToRoute('utilidades_api_rest_crear');
            } else {
                $this->addFlash('css', 'warning');
                $this->addFlash('mensaje', 'Ocurrió un error inesperado');
                return $this->redirectToRoute('utilidades_api_rest_crear');
            }
        }
        return $this->render('utilidades/api_rest_add.html.twig', compact('form'));
    }

    #[Route('/utilidades/api-rest/editar/{id}', name: 'utilidades_api_rest_editar')]
    public function api_rest_editar(Request $request, int $id): Response
    {
        $datos = $this->client->request(
            'GET',
            'https://www.api.tamila.cl/api/categorias/' . $id,
            [
                'headers' => [
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MzYsImlhdCI6MTcwNDIyMDI0MywiZXhwIjoxNzA2ODEyMjQzfQ.9tChYNr3pH_Xtr5-c7XJTvjMMhuMy7ClHLvM5kabuC0'
                ]
            ]
        );

        $form = $this->createForm(CategoriaApiType::class, null);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');

        if ($form->isSubmitted()) {
            if ($this->isCsrfTokenValid('generico', $submittedToken)) {
                $campos = $form->getData();
                $response = $this->client->request(
                    'PUT',
                    'https://www.api.tamila.cl/api/categorias/' . $id,
                    [
                        'json' => ['nombre' => $campos['nombre']],
                        'headers' => [
                            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MzYsImlhdCI6MTcwNDIyMDI0MywiZXhwIjoxNzA2ODEyMjQzfQ.9tChYNr3pH_Xtr5-c7XJTvjMMhuMy7ClHLvM5kabuC0'
                        ]
                    ]
                );
                $this->addFlash('css', 'success');
                $this->addFlash('mensaje', 'Se modificó el registro exitosamente');
                return $this->redirectToRoute('utilidades_api_rest_editar', ['id' => $id]);
            } else {
                $this->addFlash('css', 'warning');
                $this->addFlash('mensaje', 'Ocurrió un error inesperado');
                return $this->redirectToRoute('utilidades_api_rest_editar', ['id' => $id]);
            }
        }
        return $this->render('utilidades/api_rest_editar.html.twig', compact('form', 'datos'));
    }

    #[Route('/utilidades/api-rest/delete/{id}', name: 'utilidades_api_rest_delete')]
    public function api_rest_delete(Request $request, int $id): Response
    {
        $this->client->request(
            'DELETE',
            'https://www.api.tamila.cl/api/categorias/' . $id,
            [
                'headers' => [
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MzYsImlhdCI6MTcwNDIyMDI0MywiZXhwIjoxNzA2ODEyMjQzfQ.9tChYNr3pH_Xtr5-c7XJTvjMMhuMy7ClHLvM5kabuC0'
                ]
            ]
        );
        $this->addFlash('css', 'success');
        $this->addFlash('mensaje', 'Se eliminó el registro exitosamente');
        return $this->redirectToRoute('utilidades_api_rest');
    }

    #[Route('/utilidades/filesystem', name: 'utilidades_filesystem')]
    public function filesystem(): Response
    {

        $filesystem = new Filesystem();
        $ejemplo_mkdir = "/var/www/html/clientes/tamila/pruebas/symfony/midirectorio";

        if (!$filesystem->exists($ejemplo_mkdir)) {
            $filesystem->mkdir($ejemplo_mkdir, 0777);
        } else {
            $filesystem->copy('/var/www/html/clientes/tamila/pruebas/symfony/descarga_cli.png', $ejemplo_mkdir . "/descarga_cli.png");
            $filesystem->rename($ejemplo_mkdir . "/descarga_cli.png",$ejemplo_mkdir . "/descarga_cli_modificado.png");
            $filesystem->remove([$ejemplo_mkdir . "/descarga_cli_modificado.png"]);
        }
        return $this->render('utilidades/filesystem.html.twig');
    }
}
