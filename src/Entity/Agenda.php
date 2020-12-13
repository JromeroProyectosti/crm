<?php

namespace App\Entity;

use App\Repository\AgendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgendaRepository::class)
 */
class Agenda
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cuenta::class, inversedBy="agendas")
     */
    private $cuenta;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $campania;


    /**
     * @ORM\ManyToOne(targetEntity=Gestionar::class, inversedBy="agendas")
     */
    private $gestionar;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="agendas")
     */
    private $agendador;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreCliente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailCliente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefonoCliente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ciudadCliente;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaCarga;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAsignado;

    /**
     * @ORM\ManyToOne(targetEntity=Sucursal::class, inversedBy="agendas")
     */
    private $sucursal;

    /**
     * @ORM\ManyToOne(targetEntity=AgendaStatus::class, inversedBy="agendas")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detalleCliente;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="agendaAbogados")
     */
    private $abogado;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $monto;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioUsuariocategoria::class, mappedBy="agenda")
     */
    private $usuarioUsuariocategorias;

    /**
     * @ORM\ManyToOne(targetEntity=Reunion::class, inversedBy="agendas")
     */
    private $reunion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacion;

    /**
     * @ORM\OneToMany(targetEntity=AgendaObservacion::class, mappedBy="agenda", orphanRemoval=true)
     */
    private $agendaObservacions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rutCliente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefonoRecadoCliente;

    public function __construct()
    {
        $this->usuarioUsuariocategorias = new ArrayCollection();
        $this->agendaObservacions = new ArrayCollection();
    }

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

    public function getCampania(): ?string
    {
        return $this->campania;
    }

    public function setCampania(string $campania): self
    {
        $this->campania = $campania;

        return $this;
    }

    

    public function getGestionar(): ?Gestionar
    {
        return $this->gestionar;
    }

    public function setGestionar(?Gestionar $gestionar): self
    {
        $this->gestionar = $gestionar;

        return $this;
    }

    public function getAgendador(): ?Usuario
    {
        return $this->agendador;
    }

    public function setAgendador(?Usuario $agendador): self
    {
        $this->agendador = $agendador;

        return $this;
    }

    public function getNombreCliente(): ?string
    {
        return $this->nombreCliente;
    }

    public function setNombreCliente(?string $nombreCliente): self
    {
        $this->nombreCliente = $nombreCliente;

        return $this;
    }

    public function getEmailCliente(): ?string
    {
        return $this->emailCliente;
    }

    public function setEmailCliente(?string $emailCliente): self
    {
        $this->emailCliente = $emailCliente;

        return $this;
    }

    public function getTelefonoCliente(): ?string
    {
        return $this->telefonoCliente;
    }

    public function setTelefonoCliente(?string $telefonoCliente): self
    {
        $this->telefonoCliente = $telefonoCliente;

        return $this;
    }

    public function getCiudadCliente(): ?string
    {
        return $this->ciudadCliente;
    }

    public function setCiudadCliente(?string $ciudadCliente): self
    {
        $this->ciudadCliente = $ciudadCliente;

        return $this;
    }

    public function getFechaCarga(): ?\DateTimeInterface
    {
        return $this->fechaCarga;
    }

    public function setFechaCarga(?\DateTimeInterface $fechaCarga): self
    {
        $this->fechaCarga = $fechaCarga;

        return $this;
    }

    public function getFechaAsignado(): ?\DateTimeInterface
    {
        return $this->fechaAsignado;
    }

    public function setFechaAsignado(?\DateTimeInterface $fechaAsignado): self
    {
        $this->fechaAsignado = $fechaAsignado;

        return $this;
    }

    public function getSucursal(): ?Sucursal
    {
        return $this->sucursal;
    }

    public function setSucursal(?Sucursal $sucursal): self
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    public function getStatus(): ?AgendaStatus
    {
        return $this->status;
    }

    public function setStatus(?AgendaStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDetalleCliente(): ?string
    {
        return $this->detalleCliente;
    }

    public function setDetalleCliente(?string $detalleCliente): self
    {
        $this->detalleCliente = $detalleCliente;

        return $this;
    }

    public function getAbogado(): ?Usuario
    {
        return $this->abogado;
    }

    public function setAbogado(?Usuario $abogado): self
    {
        $this->abogado = $abogado;

        return $this;
    }

    public function getMonto(): ?string
    {
        return $this->monto;
    }

    public function setMonto(?string $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * @return Collection|UsuarioUsuariocategoria[]
     */
    public function getUsuarioUsuariocategorias(): Collection
    {
        return $this->usuarioUsuariocategorias;
    }

    public function addUsuarioUsuariocategoria(UsuarioUsuariocategoria $usuarioUsuariocategoria): self
    {
        if (!$this->usuarioUsuariocategorias->contains($usuarioUsuariocategoria)) {
            $this->usuarioUsuariocategorias[] = $usuarioUsuariocategoria;
            $usuarioUsuariocategoria->setAgenda($this);
        }

        return $this;
    }

    public function removeUsuarioUsuariocategoria(UsuarioUsuariocategoria $usuarioUsuariocategoria): self
    {
        if ($this->usuarioUsuariocategorias->removeElement($usuarioUsuariocategoria)) {
            // set the owning side to null (unless already changed)
            if ($usuarioUsuariocategoria->getAgenda() === $this) {
                $usuarioUsuariocategoria->setAgenda(null);
            }
        }

        return $this;
    }

    public function getReunion(): ?Reunion
    {
        return $this->reunion;
    }

    public function setReunion(?Reunion $reunion): self
    {
        $this->reunion = $reunion;

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

    /**
     * @return Collection|AgendaObservacion[]
     */
    public function getAgendaObservacions(): Collection
    {
        return $this->agendaObservacions;
    }

    public function addAgendaObservacion(AgendaObservacion $agendaObservacion): self
    {
        if (!$this->agendaObservacions->contains($agendaObservacion)) {
            $this->agendaObservacions[] = $agendaObservacion;
            $agendaObservacion->setAgenda($this);
        }

        return $this;
    }

    public function removeAgendaObservacion(AgendaObservacion $agendaObservacion): self
    {
        if ($this->agendaObservacions->removeElement($agendaObservacion)) {
            // set the owning side to null (unless already changed)
            if ($agendaObservacion->getAgenda() === $this) {
                $agendaObservacion->setAgenda(null);
            }
        }

        return $this;
    }

    public function getRutCliente(): ?string
    {
        return $this->rutCliente;
    }

    public function setRutCliente(?string $rutCliente): self
    {
        $this->rutCliente = $rutCliente;

        return $this;
    }

    public function getTelefonoRecadoCliente(): ?string
    {
        return $this->telefonoRecadoCliente;
    }

    public function setTelefonoRecadoCliente(?string $telefonoRecadoCliente): self
    {
        $this->telefonoRecadoCliente = $telefonoRecadoCliente;

        return $this;
    }
}
