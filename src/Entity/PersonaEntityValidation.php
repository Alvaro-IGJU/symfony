<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PersonaEntityValidation
{

    #[Assert\NotBlank(message:'El campo Nombre es obligatorio')]
    protected $nombre;

    #[Assert\NotBlank(message:'El campo E-Mail es obligatorio'), Assert\Email(message:'El E-Mail {{ value }} no es un correo válido')]
    protected $correo;

    #[Assert\NotBlank(message:'El campo Teléfono es obligatorio')]
    protected $telefono;

    #[Assert\Positive(message:'Debe seleccionar un país')]
    protected $pais;

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    public function getPais(): string
    {
        return $this->pais;
    }

    public function setPais(string $pais): void
    {
        $this->pais = $pais;
    }
}
