<?php

namespace App\Entity;

use App\Repository\ConfiguracionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfiguracionRepository::class)
 */
class Configuracion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $diaFondoFijo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $host;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $accessToken;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $verifyToken;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lotes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $valorMulta;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiaFondoFijo(): ?int
    {
        return $this->diaFondoFijo;
    }

    public function setDiaFondoFijo(int $diaFondoFijo): self
    {
        $this->diaFondoFijo = $diaFondoFijo;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getVerifyToken(): ?string
    {
        return $this->verifyToken;
    }

    public function setVerifyToken(?string $verifyToken): self
    {
        $this->verifyToken = $verifyToken;

        return $this;
    }

    public function getLotes(): ?int
    {
        return $this->lotes;
    }

    public function setLotes(?int $lotes): self
    {
        $this->lotes = $lotes;

        return $this;
    }

    public function getValorMulta(): ?int
    {
        return $this->valorMulta;
    }

    public function setValorMulta(?int $valorMulta): self
    {
        $this->valorMulta = $valorMulta;

        return $this;
    }
}
