<?php

namespace App\Entity;

use App\Repository\CuotaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CuotaRepository::class)
 */
class Cuota
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaPago;

    /**
     * @ORM\Column(type="integer")
     */
    private $monto;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pagado;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $anular;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAnulacion;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     */
    private $usuarioAnulacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getFechaPago(): ?\DateTimeInterface
    {
        return $this->fechaPago;
    }

    public function setFechaPago(\DateTimeInterface $fechaPago): self
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    public function getMonto(): ?int
    {
        return $this->monto;
    }

    public function setMonto(int $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    public function getPagado(): ?int
    {
        return $this->pagado;
    }

    public function setPagado(?int $pagado): self
    {
        $this->pagado = $pagado;

        return $this;
    }

    public function getAnular(): ?bool
    {
        return $this->anular;
    }

    public function setAnular(?bool $anular): self
    {
        $this->anular = $anular;

        return $this;
    }

    public function getFechaAnulacion(): ?\DateTimeInterface
    {
        return $this->fechaAnulacion;
    }

    public function setFechaAnulacion(?\DateTimeInterface $fechaAnulacion): self
    {
        $this->fechaAnulacion = $fechaAnulacion;

        return $this;
    }

    public function getUsuarioAnulacion(): ?Usuario
    {
        return $this->usuarioAnulacion;
    }

    public function setUsuarioAnulacion(?Usuario $usuarioAnulacion): self
    {
        $this->usuarioAnulacion = $usuarioAnulacion;

        return $this;
    }
}
