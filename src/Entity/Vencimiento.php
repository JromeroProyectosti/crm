<?php

namespace App\Entity;

use App\Repository\VencimientoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VencimientoRepository::class)
 */
class Vencimiento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $valMin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $valMax;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icono;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $empresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValMin(): ?int
    {
        return $this->valMin;
    }

    public function setValMin(?int $valMin): self
    {
        $this->valMin = $valMin;

        return $this;
    }

    public function getValMax(): ?int
    {
        return $this->valMax;
    }

    public function setValMax(?int $valMax): self
    {
        $this->valMax = $valMax;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getIcono(): ?string
    {
        return $this->icono;
    }

    public function setIcono(?string $icono): self
    {
        $this->icono = $icono;

        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }
}
