<?php

namespace App\Entity;

use App\Repository\ContratoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratoRepository::class)
 */
class Contrato
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $ciudad;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $rut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comuna;

    /**
     * @ORM\ManyToOne(targetEntity=EstadoCivil::class, inversedBy="contratos")
     */
    private $estadoCivil;

    /**
     * @ORM\ManyToOne(targetEntity=SituacionLaboral::class, inversedBy="contratos")
     */
    private $situacionLaboral;

    /**
     * @ORM\ManyToOne(targetEntity=EstrategiaJuridica::class, inversedBy="contratos")
     */
    private $estrategiaJuridica;

    /**
     * @ORM\ManyToOne(targetEntity=Escritura::class, inversedBy="contratos")
     */
    private $escritura;

    /**
     * @ORM\OneToOne(targetEntity=Agenda::class, cascade={"persist", "remove"})
     */
    private $agenda;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tituloContrato;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $montoNivelDeuda;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $MontoContrato;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuotas;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $valorCuota;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $interes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $diaPago;

    /**
     * @ORM\OneToMany(targetEntity=ContratoRol::class, mappedBy="contrato")
     */
    private $contratoRols;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @ORM\ManyToOne(targetEntity=Sucursal::class, inversedBy="contratos")
     */
    private $sucursal;

    
    private $contratoTramitadores;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="contratos")
     */
    private $tramitador;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="usuarioContratos")
     */
    private $cliente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $claveUnica;

    /**
     * @ORM\ManyToOne(targetEntity=Pais::class, inversedBy="contratos")
     */
    private $pais;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefonoRecado;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaPrimerPago;

    /**
     * @ORM\ManyToOne(targetEntity=ContratoVehiculo::class, inversedBy="contratos")
     */
    private $vehiculo;

    /**
     * @ORM\ManyToOne(targetEntity=ContratoVivienda::class, inversedBy="contratos")
     */
    private $vivienda;

    /**
     * @ORM\ManyToOne(targetEntity=Reunion::class, inversedBy="contratos")
     */
    private $reunion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $primeraCuota;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaPrimeraCuota;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdf;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAbono;

    /**
     * @ORM\OneToMany(targetEntity=Cuota::class, mappedBy="contrato", orphanRemoval=true)
     */
    private $detalleCuotas;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaUltimoPago;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFinalizado;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdfTermino;

    /**
     * @ORM\OneToMany(targetEntity=ContratoAnexo::class, mappedBy="contrato")
     */
    private $contratoAnexos;

    
    public function __construct()
    {
        $this->contratoRols = new ArrayCollection();
        $this->detalleCuotas = new ArrayCollection();
        $this->contratoAnexos = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getRut(): ?string
    {
        return $this->rut;
    }

    public function setRut(?string $rut): self
    {
        $this->rut = $rut;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getComuna(): ?string
    {
        return $this->comuna;
    }

    public function setComuna(string $comuna): self
    {
        $this->comuna = $comuna;

        return $this;
    }

    public function getEstadoCivil(): ?EstadoCivil
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(?EstadoCivil $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    public function getSituacionLaboral(): ?SituacionLaboral
    {
        return $this->situacionLaboral;
    }

    public function setSituacionLaboral(?SituacionLaboral $situacionLaboral): self
    {
        $this->situacionLaboral = $situacionLaboral;

        return $this;
    }

    public function getEstrategiaJuridica(): ?EstrategiaJuridica
    {
        return $this->estrategiaJuridica;
    }

    public function setEstrategiaJuridica(?EstrategiaJuridica $estrategiaJuridica): self
    {
        $this->estrategiaJuridica = $estrategiaJuridica;

        return $this;
    }

    public function getEscritura(): ?Escritura
    {
        return $this->escritura;
    }

    public function setEscritura(?Escritura $escritura): self
    {
        $this->escritura = $escritura;

        return $this;
    }

    public function getAgenda(): ?Agenda
    {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }

    public function getTituloContrato(): ?string
    {
        return $this->tituloContrato;
    }

    public function setTituloContrato(?string $tituloContrato): self
    {
        $this->tituloContrato = $tituloContrato;

        return $this;
    }

    public function getMontoNivelDeuda(): ?string
    {
        return $this->montoNivelDeuda;
    }

    public function setMontoNivelDeuda(?string $montoNivelDeuda): self
    {
        $this->montoNivelDeuda = $montoNivelDeuda;

        return $this;
    }

    public function getMontoContrato(): ?string
    {
        return $this->MontoContrato;
    }

    public function setMontoContrato(?string $MontoContrato): self
    {
        $this->MontoContrato = $MontoContrato;

        return $this;
    }

    public function getCuotas(): ?int
    {
        return $this->cuotas;
    }

    public function setCuotas(?int $cuotas): self
    {
        $this->cuotas = $cuotas;

        return $this;
    }

    public function getValorCuota(): ?string
    {
        return $this->valorCuota;
    }

    public function setValorCuota(?string $valorCuota): self
    {
        $this->valorCuota = $valorCuota;

        return $this;
    }

    public function getInteres(): ?string
    {
        return $this->interes;
    }

    public function setInteres(?string $interes): self
    {
        $this->interes = $interes;

        return $this;
    }

    public function getDiaPago(): ?int
    {
        return $this->diaPago;
    }

    public function setDiaPago(?int $diaPago): self
    {
        $this->diaPago = $diaPago;

        return $this;
    }

    /**
     * @return Collection|ContratoRol[]
     */
    public function getContratoRols(): Collection
    {
        return $this->contratoRols;
    }

    public function addContratoRol(ContratoRol $contratoRol): self
    {
        if (!$this->contratoRols->contains($contratoRol)) {
            $this->contratoRols[] = $contratoRol;
            $contratoRol->setContrato($this);
        }

        return $this;
    }

    public function removeContratoRol(ContratoRol $contratoRol): self
    {
        if ($this->contratoRols->removeElement($contratoRol)) {
            // set the owning side to null (unless already changed)
            if ($contratoRol->getContrato() === $this) {
                $contratoRol->setContrato(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getId()." ".$this->getNombre();
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(?\DateTimeInterface $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

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

    public function getTramitador(): ?Usuario
    {
        return $this->tramitador;
    }

    public function setTramitador(?Usuario $tramitador): self
    {
        $this->tramitador = $tramitador;

        return $this;
    }

    public function getCliente(): ?Usuario
    {
        return $this->cliente;
    }

    public function setCliente(?Usuario $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getClaveUnica(): ?string
    {
        return $this->claveUnica;
    }

    public function setClaveUnica(?string $claveUnica): self
    {
        $this->claveUnica = $claveUnica;

        return $this;
    }

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getTelefonoRecado(): ?string
    {
        return $this->telefonoRecado;
    }

    public function setTelefonoRecado(?string $telefonoRecado): self
    {
        $this->telefonoRecado = $telefonoRecado;

        return $this;
    }

    public function getFechaPrimerPago(): ?\DateTimeInterface
    {
        return $this->fechaPrimerPago;
    }

    public function setFechaPrimerPago(?\DateTimeInterface $fechaPrimerPago): self
    {
        $this->fechaPrimerPago = $fechaPrimerPago;

        return $this;
    }

    public function getVehiculo(): ?ContratoVehiculo
    {
        return $this->vehiculo;
    }

    public function setVehiculo(?ContratoVehiculo $vehiculo): self
    {
        $this->vehiculo = $vehiculo;

        return $this;
    }

    public function getVivienda(): ?ContratoVivienda
    {
        return $this->vivienda;
    }

    public function setVivienda(?ContratoVivienda $vivienda): self
    {
        $this->vivienda = $vivienda;

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

    public function getPrimeraCuota(): ?float
    {
        return $this->primeraCuota;
    }

    public function setPrimeraCuota(?float $primeraCuota): self
    {
        $this->primeraCuota = $primeraCuota;

        return $this;
    }

    public function getFechaPrimeraCuota(): ?\DateTimeInterface
    {
        return $this->fechaPrimeraCuota;
    }

    public function setFechaPrimeraCuota(?\DateTimeInterface $fechaPrimeraCuota): self
    {
        $this->fechaPrimeraCuota = $fechaPrimeraCuota;

        return $this;
    }

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(?string $pdf): self
    {
        $this->pdf = $pdf;

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

    public function getIsAbono(): ?bool
    {
        return $this->isAbono;
    }

    public function setIsAbono(?bool $isAbono): self
    {
        $this->isAbono = $isAbono;

        return $this;
    }

    /**
     * @return Collection|Cuota[]
     */
    public function getDetalleCuotas(): Collection
    {
        return $this->detalleCuotas;
    }

    public function addDetalleCuota(Cuota $detalleCuota): self
    {
        if (!$this->detalleCuotas->contains($detalleCuota)) {
            $this->detalleCuotas[] = $detalleCuota;
            $detalleCuota->setContrato($this);
        }

        return $this;
    }

    public function removeDetalleCuota(Cuota $detalleCuota): self
    {
        if ($this->detalleCuotas->removeElement($detalleCuota)) {
            // set the owning side to null (unless already changed)
            if ($detalleCuota->getContrato() === $this) {
                $detalleCuota->setContrato(null);
            }
        }

        return $this;
    }

    public function getFechaUltimoPago(): ?\DateTimeInterface
    {
        return $this->fechaUltimoPago;
    }

    public function setFechaUltimoPago(?\DateTimeInterface $fechaUltimoPago): self
    {
        $this->fechaUltimoPago = $fechaUltimoPago;

        return $this;
    }

    public function getIsFinalizado(): ?bool
    {
        return $this->isFinalizado;
    }

    public function setIsFinalizado(?bool $isFinalizado): self
    {
        $this->isFinalizado = $isFinalizado;

        return $this;
    }

    public function getLote(): ?int
    {
        return $this->lote;
    }

    public function setLote(?int $lote): self
    {
        $this->lote = $lote;

        return $this;
    }

    public function getPdfTermino(): ?string
    {
        return $this->pdfTermino;
    }

    public function setPdfTermino(?string $pdfTermino): self
    {
        $this->pdfTermino = $pdfTermino;

        return $this;
    }

    /**
     * @return Collection|ContratoAnexo[]
     */
    public function getContratoAnexos(): Collection
    {
        return $this->contratoAnexos;
    }

    public function addContratoAnexo(ContratoAnexo $contratoAnexo): self
    {
        if (!$this->contratoAnexos->contains($contratoAnexo)) {
            $this->contratoAnexos[] = $contratoAnexo;
            $contratoAnexo->setContrato($this);
        }

        return $this;
    }

    public function removeContratoAnexo(ContratoAnexo $contratoAnexo): self
    {
        if ($this->contratoAnexos->removeElement($contratoAnexo)) {
            // set the owning side to null (unless already changed)
            if ($contratoAnexo->getContrato() === $this) {
                $contratoAnexo->setContrato(null);
            }
        }

        return $this;
    }


    
}
