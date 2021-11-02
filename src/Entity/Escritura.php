<?php

namespace App\Entity;

use App\Repository\EscrituraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EscrituraRepository::class)
 */
class Escritura
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
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="escrituras")
     */
    private $empresa;

    /**
     * @ORM\OneToMany(targetEntity=Contrato::class, mappedBy="escritura")
     */
    private $contratos;

    /**
     * @ORM\OneToMany(targetEntity=Mee::class, mappedBy="escritura")
     */
    private $mees;

    public function __construct()
    {
        $this->contratos = new ArrayCollection();
        $this->mees = new ArrayCollection();
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

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * @return Collection|Contrato[]
     */
    public function getContratos(): Collection
    {
        return $this->contratos;
    }

    public function addContrato(Contrato $contrato): self
    {
        if (!$this->contratos->contains($contrato)) {
            $this->contratos[] = $contrato;
            $contrato->setEscritura($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $contrato): self
    {
        if ($this->contratos->removeElement($contrato)) {
            // set the owning side to null (unless already changed)
            if ($contrato->getEscritura() === $this) {
                $contrato->setEscritura(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getNombre();
    }

    /**
     * @return Collection|Mee[]
     */
    public function getMees(): Collection
    {
        return $this->mees;
    }

    public function addMee(Mee $mee): self
    {
        if (!$this->mees->contains($mee)) {
            $this->mees[] = $mee;
            $mee->setEscritura($this);
        }

        return $this;
    }

    public function removeMee(Mee $mee): self
    {
        if ($this->mees->removeElement($mee)) {
            // set the owning side to null (unless already changed)
            if ($mee->getEscritura() === $this) {
                $mee->setEscritura(null);
            }
        }

        return $this;
    }
}
