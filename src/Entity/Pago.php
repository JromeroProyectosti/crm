<?php

namespace App\Entity;

use App\Repository\PagoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PagoRepository::class)
 */
class Pago
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PagoTipo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pagoTipo;

    /**
     * @ORM\ManyToOne(targetEntity=PagoCanal::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pagoCanal;

    /**
     * @ORM\Column(type="integer")
     */
    private $monto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $boleta;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaPago;

    /**
     * @ORM\Column(type="time")
     */
    private $horaPago;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaRegistro;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuarioRegistro;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPagoTipo(): ?PagoTipo
    {
        return $this->pagoTipo;
    }

    public function setPagoTipo(?PagoTipo $pagoTipo): self
    {
        $this->pagoTipo = $pagoTipo;

        return $this;
    }

    public function getPagoCanal(): ?PagoCanal
    {
        return $this->pagoCanal;
    }

    public function setPagoCanal(?PagoCanal $pagoCanal): self
    {
        $this->pagoCanal = $pagoCanal;

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

    public function getBoleta(): ?string
    {
        return $this->boleta;
    }

    public function setBoleta(string $boleta): self
    {
        $this->boleta = $boleta;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): self
    {
        $this->observacion = $observacion;

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

    public function getHoraPago(): ?\DateTimeInterface
    {
        return $this->horaPago;
    }

    public function setHoraPago(\DateTimeInterface $horaPago): self
    {
        $this->horaPago = $horaPago;

        return $this;
    }

    public function getFechaRegistro(): ?\DateTimeInterface
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(\DateTimeInterface $fechaRegistro): self
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    public function getUsuarioRegistro(): ?Usuario
    {
        return $this->usuarioRegistro;
    }

    public function setUsuarioRegistro(?Usuario $usuarioRegistro): self
    {
        $this->usuarioRegistro = $usuarioRegistro;

        return $this;
    }
}
