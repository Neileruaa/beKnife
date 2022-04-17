<?php

namespace App\Entity;

use App\Repository\TailleCouteauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TailleCouteauRepository::class)
 */
class TailleCouteau
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
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $taille;

    /**
     * @ORM\OneToMany(targetEntity=Couteau::class, mappedBy="taille")
     */
    private $couteaus;

    public function __construct()
    {
        $this->couteaus = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->libelle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * @return Collection<int, Couteau>
     */
    public function getCouteaus(): Collection
    {
        return $this->couteaus;
    }

    public function addCouteau(Couteau $couteau): self
    {
        if (!$this->couteaus->contains($couteau)) {
            $this->couteaus[] = $couteau;
            $couteau->setTaille($this);
        }

        return $this;
    }

    public function removeCouteau(Couteau $couteau): self
    {
        if ($this->couteaus->removeElement($couteau)) {
            // set the owning side to null (unless already changed)
            if ($couteau->getTaille() === $this) {
                $couteau->setTaille(null);
            }
        }

        return $this;
    }
}
