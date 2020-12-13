<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmpresaRepository::class)
 */
class Empresa
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rol;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $rut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaUltimamodificacion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaVigencia;

    /**
     * @ORM\OneToMany(targetEntity=Cuenta::class, mappedBy="empresa")
     */
    private $cuentas;

    /**
     * @ORM\OneToMany(targetEntity=Modulo::class, mappedBy="empresa", orphanRemoval=true)
     */
    private $modulos;

    /**
     * @ORM\OneToMany(targetEntity=Accion::class, mappedBy="empresa")
     */
    private $acciones;

    /**
     * @ORM\OneToMany(targetEntity=Menu::class, mappedBy="empresa", orphanRemoval=true)
     */
    private $menus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logoAlt;

    /**
     * @ORM\OneToMany(targetEntity=ModuloPer::class, mappedBy="empresa", orphanRemoval=true)
     */
    private $moduloPers;

    /**
     * @ORM\OneToMany(targetEntity=MenuCabezera::class, mappedBy="empresa")
     */
    private $menuCabezeras;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioTipo::class, mappedBy="empresa")
     */
    private $usuarioTipos;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioCategoria::class, mappedBy="empresa")
     */
    private $usuarioCategorias;

    /**
     * @ORM\OneToMany(targetEntity=EstadoCivil::class, mappedBy="empresa")
     */
    private $estadoCivils;

    /**
     * @ORM\OneToMany(targetEntity=SituacionLaboral::class, mappedBy="empresa")
     */
    private $situacionLaborals;

    /**
     * @ORM\OneToMany(targetEntity=EstrategiaJuridica::class, mappedBy="empresa")
     */
    private $estrategiaJuridicas;

    /**
     * @ORM\OneToMany(targetEntity=Escritura::class, mappedBy="empresa")
     */
    private $escrituras;

    /**
     * @ORM\OneToMany(targetEntity=Juzgado::class, mappedBy="empresa")
     */
    private $juzgados;

    /**
     * @ORM\OneToMany(targetEntity=Reunion::class, mappedBy="empresa")
     */
    private $reunions;

    /**
     * @ORM\OneToMany(targetEntity=Pais::class, mappedBy="empresa")
     */
    private $pais;

    /**
     * @ORM\OneToMany(targetEntity=ContratoVivienda::class, mappedBy="empresa")
     */
    private $contratoViviendas;

    /**
     * @ORM\OneToMany(targetEntity=ContratoVehiculo::class, mappedBy="empresa")
     */
    private $contratoVehiculos;

    public function __construct()
    {
        $this->cuentas = new ArrayCollection();
        $this->acciones = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->moduloPers = new ArrayCollection();
        $this->menuCabezeras = new ArrayCollection();
        $this->usuarioTipos = new ArrayCollection();
        $this->usuarioCategorias = new ArrayCollection();
        $this->estadoCivils = new ArrayCollection();
        $this->situacionLaborals = new ArrayCollection();
        $this->estrategiaJuridicas = new ArrayCollection();
        $this->escrituras = new ArrayCollection();
        $this->juzgados = new ArrayCollection();
        $this->reunions = new ArrayCollection();
        $this->pais = new ArrayCollection();
        $this->contratoViviendas = new ArrayCollection();
        $this->contratoVehiculos = new ArrayCollection();
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

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(?string $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getRut(): ?string
    {
        return $this->rut;
    }

    public function setRut(string $rut): self
    {
        $this->rut = $rut;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getFechaIngreso(): ?\DateTimeInterface
    {
        return $this->fechaIngreso;
    }

    public function setFechaIngreso(\DateTimeInterface $fechaIngreso): self
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    public function getFechaUltimamodificacion(): ?\DateTimeInterface
    {
        return $this->fechaUltimamodificacion;
    }

    public function setFechaUltimamodificacion(?\DateTimeInterface $fechaUltimamodificacion): self
    {
        $this->fechaUltimamodificacion = $fechaUltimamodificacion;

        return $this;
    }

    public function getFechaVigencia(): ?\DateTimeInterface
    {
        return $this->fechaVigencia;
    }

    public function setFechaVigencia(\DateTimeInterface $fechaVigencia): self
    {
        $this->fechaVigencia = $fechaVigencia;

        return $this;
    }

    /**
     * @return Collection|Cuenta[]
     */
    public function getCuentas(): Collection
    {
        return $this->cuentas;
    }

    public function addCuenta(Cuenta $cuenta): self
    {
        if (!$this->cuentas->contains($cuenta)) {
            $this->cuentas[] = $cuenta;
            $cuenta->setEmpresa($this);
        }

        return $this;
    }

    public function removeCuenta(Cuenta $cuenta): self
    {
        if ($this->cuentas->removeElement($cuenta)) {
            // set the owning side to null (unless already changed)
            if ($cuenta->getEmpresa() === $this) {
                $cuenta->setEmpresa(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getNombre();
    }

    

    /**
     * @return Collection|Accion[]
     */
    public function getAcciones(): Collection
    {
        return $this->acciones;
    }

    public function addAccione(Accion $accione): self
    {
        if (!$this->acciones->contains($accione)) {
            $this->acciones[] = $accione;
            $accione->setEmpresa($this);
        }

        return $this;
    }

    public function removeAccione(Accion $accione): self
    {
        if ($this->acciones->removeElement($accione)) {
            // set the owning side to null (unless already changed)
            if ($accione->getEmpresa() === $this) {
                $accione->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setEmpresa($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getEmpresa() === $this) {
                $menu->setEmpresa(null);
            }
        }

        return $this;
    }

    public function getLogoAlt(): ?string
    {
        return $this->logoAlt;
    }

    public function setLogoAlt(?string $logoAlt): self
    {
        $this->logoAlt = $logoAlt;

        return $this;
    }

    /**
     * @return Collection|ModuloPer[]
     */
    public function getModuloPers(): Collection
    {
        return $this->moduloPers;
    }

    public function addModuloPer(ModuloPer $moduloPer): self
    {
        if (!$this->moduloPers->contains($moduloPer)) {
            $this->moduloPers[] = $moduloPer;
            $moduloPer->setEmpresa($this);
        }

        return $this;
    }

    public function removeModuloPer(ModuloPer $moduloPer): self
    {
        if ($this->moduloPers->removeElement($moduloPer)) {
            // set the owning side to null (unless already changed)
            if ($moduloPer->getEmpresa() === $this) {
                $moduloPer->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MenuCabezera[]
     */
    public function getMenuCabezeras(): Collection
    {
        return $this->menuCabezeras;
    }

    public function addMenuCabezera(MenuCabezera $menuCabezera): self
    {
        if (!$this->menuCabezeras->contains($menuCabezera)) {
            $this->menuCabezeras[] = $menuCabezera;
            $menuCabezera->setEmpresa($this);
        }

        return $this;
    }

    public function removeMenuCabezera(MenuCabezera $menuCabezera): self
    {
        if ($this->menuCabezeras->removeElement($menuCabezera)) {
            // set the owning side to null (unless already changed)
            if ($menuCabezera->getEmpresa() === $this) {
                $menuCabezera->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UsuarioTipo[]
     */
    public function getUsuarioTipos(): Collection
    {
        return $this->usuarioTipos;
    }

    public function addUsuarioTipo(UsuarioTipo $usuarioTipo): self
    {
        if (!$this->usuarioTipos->contains($usuarioTipo)) {
            $this->usuarioTipos[] = $usuarioTipo;
            $usuarioTipo->setEmpresa($this);
        }

        return $this;
    }

    public function removeUsuarioTipo(UsuarioTipo $usuarioTipo): self
    {
        if ($this->usuarioTipos->removeElement($usuarioTipo)) {
            // set the owning side to null (unless already changed)
            if ($usuarioTipo->getEmpresa() === $this) {
                $usuarioTipo->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UsuarioCategoria[]
     */
    public function getUsuarioCategorias(): Collection
    {
        return $this->usuarioCategorias;
    }

    public function addUsuarioCategoria(UsuarioCategoria $usuarioCategoria): self
    {
        if (!$this->usuarioCategorias->contains($usuarioCategoria)) {
            $this->usuarioCategorias[] = $usuarioCategoria;
            $usuarioCategoria->setEmpresa($this);
        }

        return $this;
    }

    public function removeUsuarioCategoria(UsuarioCategoria $usuarioCategoria): self
    {
        if ($this->usuarioCategorias->removeElement($usuarioCategoria)) {
            // set the owning side to null (unless already changed)
            if ($usuarioCategoria->getEmpresa() === $this) {
                $usuarioCategoria->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EstadoCivil[]
     */
    public function getEstadoCivils(): Collection
    {
        return $this->estadoCivils;
    }

    public function addEstadoCivil(EstadoCivil $estadoCivil): self
    {
        if (!$this->estadoCivils->contains($estadoCivil)) {
            $this->estadoCivils[] = $estadoCivil;
            $estadoCivil->setEmpresa($this);
        }

        return $this;
    }

    public function removeEstadoCivil(EstadoCivil $estadoCivil): self
    {
        if ($this->estadoCivils->removeElement($estadoCivil)) {
            // set the owning side to null (unless already changed)
            if ($estadoCivil->getEmpresa() === $this) {
                $estadoCivil->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SituacionLaboral[]
     */
    public function getSituacionLaborals(): Collection
    {
        return $this->situacionLaborals;
    }

    public function addSituacionLaboral(SituacionLaboral $situacionLaboral): self
    {
        if (!$this->situacionLaborals->contains($situacionLaboral)) {
            $this->situacionLaborals[] = $situacionLaboral;
            $situacionLaboral->setEmpresa($this);
        }

        return $this;
    }

    public function removeSituacionLaboral(SituacionLaboral $situacionLaboral): self
    {
        if ($this->situacionLaborals->removeElement($situacionLaboral)) {
            // set the owning side to null (unless already changed)
            if ($situacionLaboral->getEmpresa() === $this) {
                $situacionLaboral->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EstrategiaJuridica[]
     */
    public function getEstrategiaJuridicas(): Collection
    {
        return $this->estrategiaJuridicas;
    }

    public function addEstrategiaJuridica(EstrategiaJuridica $estrategiaJuridica): self
    {
        if (!$this->estrategiaJuridicas->contains($estrategiaJuridica)) {
            $this->estrategiaJuridicas[] = $estrategiaJuridica;
            $estrategiaJuridica->setEmpresa($this);
        }

        return $this;
    }

    public function removeEstrategiaJuridica(EstrategiaJuridica $estrategiaJuridica): self
    {
        if ($this->estrategiaJuridicas->removeElement($estrategiaJuridica)) {
            // set the owning side to null (unless already changed)
            if ($estrategiaJuridica->getEmpresa() === $this) {
                $estrategiaJuridica->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Escritura[]
     */
    public function getEscrituras(): Collection
    {
        return $this->escrituras;
    }

    public function addEscritura(Escritura $escritura): self
    {
        if (!$this->escrituras->contains($escritura)) {
            $this->escrituras[] = $escritura;
            $escritura->setEmpresa($this);
        }

        return $this;
    }

    public function removeEscritura(Escritura $escritura): self
    {
        if ($this->escrituras->removeElement($escritura)) {
            // set the owning side to null (unless already changed)
            if ($escritura->getEmpresa() === $this) {
                $escritura->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Juzgado[]
     */
    public function getJuzgados(): Collection
    {
        return $this->juzgados;
    }

    public function addJuzgado(Juzgado $juzgado): self
    {
        if (!$this->juzgados->contains($juzgado)) {
            $this->juzgados[] = $juzgado;
            $juzgado->setEmpresa($this);
        }

        return $this;
    }

    public function removeJuzgado(Juzgado $juzgado): self
    {
        if ($this->juzgados->removeElement($juzgado)) {
            // set the owning side to null (unless already changed)
            if ($juzgado->getEmpresa() === $this) {
                $juzgado->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reunion[]
     */
    public function getReunions(): Collection
    {
        return $this->reunions;
    }

    public function addReunion(Reunion $reunion): self
    {
        if (!$this->reunions->contains($reunion)) {
            $this->reunions[] = $reunion;
            $reunion->setEmpresa($this);
        }

        return $this;
    }

    public function removeReunion(Reunion $reunion): self
    {
        if ($this->reunions->removeElement($reunion)) {
            // set the owning side to null (unless already changed)
            if ($reunion->getEmpresa() === $this) {
                $reunion->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Pais[]
     */
    public function getPais(): Collection
    {
        return $this->pais;
    }

    public function addPai(Pais $pai): self
    {
        if (!$this->pais->contains($pai)) {
            $this->pais[] = $pai;
            $pai->setEmpresa($this);
        }

        return $this;
    }

    public function removePai(Pais $pai): self
    {
        if ($this->pais->removeElement($pai)) {
            // set the owning side to null (unless already changed)
            if ($pai->getEmpresa() === $this) {
                $pai->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContratoVivienda[]
     */
    public function getContratoViviendas(): Collection
    {
        return $this->contratoViviendas;
    }

    public function addContratoVivienda(ContratoVivienda $contratoVivienda): self
    {
        if (!$this->contratoViviendas->contains($contratoVivienda)) {
            $this->contratoViviendas[] = $contratoVivienda;
            $contratoVivienda->setEmpresa($this);
        }

        return $this;
    }

    public function removeContratoVivienda(ContratoVivienda $contratoVivienda): self
    {
        if ($this->contratoViviendas->removeElement($contratoVivienda)) {
            // set the owning side to null (unless already changed)
            if ($contratoVivienda->getEmpresa() === $this) {
                $contratoVivienda->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContratoVehiculo[]
     */
    public function getContratoVehiculos(): Collection
    {
        return $this->contratoVehiculos;
    }

    public function addContratoVehiculo(ContratoVehiculo $contratoVehiculo): self
    {
        if (!$this->contratoVehiculos->contains($contratoVehiculo)) {
            $this->contratoVehiculos[] = $contratoVehiculo;
            $contratoVehiculo->setEmpresa($this);
        }

        return $this;
    }

    public function removeContratoVehiculo(ContratoVehiculo $contratoVehiculo): self
    {
        if ($this->contratoVehiculos->removeElement($contratoVehiculo)) {
            // set the owning side to null (unless already changed)
            if ($contratoVehiculo->getEmpresa() === $this) {
                $contratoVehiculo->setEmpresa(null);
            }
        }

        return $this;
    }
}
