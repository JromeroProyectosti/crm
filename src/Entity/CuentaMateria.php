<?php

namespace App\Entity;

use App\Repository\CuentaMateriaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CuentaMateriaRepository::class)
 */
class CuentaMateria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cuenta::class, inversedBy="cuentaMaterias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cuenta;

    /**
     * @ORM\ManyToOne(targetEntity=Materia::class, inversedBy="cuentaMaterias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $materia;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCuenta(): ?Cuenta
    {
        return $this->cuenta;
    }

    public function setCuenta(?Cuenta $cuenta): self
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    public function getMateria(): ?Materia
    {
        return $this->materia;
    }

    public function setMateria(?Materia $materia): self
    {
        $this->materia = $materia;

        return $this;
    }
}
