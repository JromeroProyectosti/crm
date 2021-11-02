<?php

namespace App\Entity;

use App\Repository\ContratoMeeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratoMeeRepository::class)
 */
class ContratoMee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Contrato::class, inversedBy="contratoMees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contrato;

    /**
     * @ORM\ManyToOne(targetEntity=Mee::class, inversedBy="contratoMees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mee;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMee(): ?Mee
    {
        return $this->mee;
    }

    public function setMee(?Mee $mee): self
    {
        $this->mee = $mee;

        return $this;
    }
}
