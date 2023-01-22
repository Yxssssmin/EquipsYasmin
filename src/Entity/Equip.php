<?php

namespace App\Entity;

use App\Repository\EquipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipRepository::class)]
class Equip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:50, unique:true)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    private ?string $cicle = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    private ?string $curs = null;

    #[ORM\Column(length: 255)]
    private ?string $imatge = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual(10, message:'La nota ha de ser menor igual que 10')]
    #[Assert\GreaterThanOrEqual(0, message:'La nota ha de ser major igual que 0')]
    private ?string $nota = null;

    #[ORM\OneToMany(mappedBy: 'equip', targetEntity: Membre::class)]
    private Collection $membres;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCicle(): ?string
    {
        return $this->cicle;
    }

    public function setCicle(string $cicle): self
    {
        $this->cicle = $cicle;

        return $this;
    }

    public function getCurs(): ?string
    {
        return $this->curs;
    }

    public function setCurs(string $curs): self
    {
        $this->curs = $curs;

        return $this;
    }

    public function getImatge(): ?string
    {
        return $this->imatge;
    }

    public function setImatge(string $imatge): self
    {
        $this->imatge = $imatge;

        return $this;
    }

    public function getNota(): ?string
    {
        return $this->nota;
    }

    public function setNota(?string $nota): self
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * @return Collection<int, Membre>
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Membre $membre): self
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
            $membre->setEquip($this);
        }

        return $this;
    }

    public function removeMembre(Membre $membre): self
    {
        if ($this->membres->removeElement($membre)) {
            // set the owning side to null (unless already changed)
            if ($membre->getEquip() === $this) {
                $membre->setEquip(null);
            }
        }

        return $this;
    }
}