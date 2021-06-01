<?php

namespace App\Entity;

use App\Repository\ContratoAnexoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratoAnexoRepository::class)
 */
class ContratoAnexo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaCreacion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdf;

    /**
     * @ORM\ManyToOne(targetEntity=Contrato::class, inversedBy="contratoAnexos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contrato;

    /**
     * @ORM\OneToMany(targetEntity=Cuota::class, mappedBy="anexo")
     */
    private $cuotas;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDesiste;

    public function __construct()
    {
        $this->cuotas = new ArrayCollection();
    }
    public function setId(?int $id):self 
    {
        $this->id=$id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

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

    public function getContrato(): ?Contrato
    {
        return $this->contrato;
    }

    public function setContrato(?Contrato $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
    }

    /**
     * @return Collection|Cuota[]
     */
    public function getCuotas(): Collection
    {
        return $this->cuotas;
    }

    public function addCuota(Cuota $cuota): self
    {
        if (!$this->cuotas->contains($cuota)) {
            $this->cuotas[] = $cuota;
            $cuota->setAnexo($this);
        }

        return $this;
    }

    public function removeCuota(Cuota $cuota): self
    {
        if ($this->cuotas->removeElement($cuota)) {
            // set the owning side to null (unless already changed)
            if ($cuota->getAnexo() === $this) {
                $cuota->setAnexo(null);
            }
        }

        return $this;
    }

    public function getIsDesiste(): ?bool
    {
        return $this->isDesiste;
    }

    public function setIsDesiste(bool $isDesiste): self
    {
        $this->isDesiste = $isDesiste;

        return $this;
    }
}
