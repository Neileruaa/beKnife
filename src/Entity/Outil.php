<?php

namespace App\Entity;

use App\Repository\OutilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutilRepository::class)
 */
class Outil
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCentral;

    /**
     * @ORM\OneToMany(targetEntity=CouteauOutil::class, mappedBy="outil", orphanRemoval=true)
     */
    private $couteauOutils;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prix;

    public function __construct()
    {
        $this->couteauOutils = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->nom;
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

    public function getIsCentral(): ?bool
    {
        return $this->isCentral;
    }

    public function setIsCentral(?bool $isCentral): self
    {
        $this->isCentral = $isCentral;

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
            $couteauOutil->setOutil($this);
        }

        return $this;
    }

    public function removeCouteauOutil(CouteauOutil $couteauOutil): self
    {
        if ($this->couteauOutils->removeElement($couteauOutil)) {
            // set the owning side to null (unless already changed)
            if ($couteauOutil->getOutil() === $this) {
                $couteauOutil->setOutil(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
