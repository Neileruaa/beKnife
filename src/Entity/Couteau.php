<?php

namespace App\Entity;

use App\Repository\CouteauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CouteauRepository::class)
 */
class Couteau
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
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=TailleCouteau::class, inversedBy="couteaus")
     */
    private $taille;

    /**
     * @ORM\OneToMany(targetEntity=CouteauOutil::class, mappedBy="couteau", orphanRemoval=true)
     */
    private $couteauOutils;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;

    public function __construct()
    {
        $this->couteauOutils = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getTaille(): ?TailleCouteau
    {
        return $this->taille;
    }

    public function setTaille(?TailleCouteau $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * @return Collection<int, CouteauOutil>
     */
    public function getCouteauOutils(): Collection
    {
        return $this->couteauOutils;
    }

    public function addCouteauOutil(CouteauOutil $couteauOutil): self
    {
        if (!$this->couteauOutils->contains($couteauOutil)) {
            $this->couteauOutils[] = $couteauOutil;
            $couteauOutil->setCouteau($this);
        }

        return $this;
    }

    public function removeCouteauOutil(CouteauOutil $couteauOutil): self
    {
        if ($this->couteauOutils->removeElement($couteauOutil)) {
            // set the owning side to null (unless already changed)
            if ($couteauOutil->getCouteau() === $this) {
                $couteauOutil->setCouteau(null);
            }
        }

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }
}
