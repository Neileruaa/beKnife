<?php

namespace App\Entity;

use App\Repository\CouteauOutilRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CouteauOutilRepository::class)
 */
class CouteauOutil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Couteau::class, inversedBy="couteauOutils")
     * @ORM\JoinColumn(nullable=false)
     */
    private $couteau;

    /**
     * @ORM\ManyToOne(targetEntity=Outil::class, inversedBy="couteauOutils")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Unique
     */
    private $outil;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDroite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isGauche;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouteau(): ?Couteau
    {
        return $this->couteau;
    }

    public function setCouteau(?Couteau $couteau): self
    {
        $this->couteau = $couteau;

        return $this;
    }

    public function getOutil(): ?Outil
    {
        return $this->outil;
    }

    public function setOutil(?Outil $outil): self
    {
        $this->outil = $outil;

        return $this;
    }

    public function getIsDroite(): ?bool
    {
        return $this->isDroite;
    }

    public function setIsDroite(?bool $isDroite): self
    {
        $this->isDroite = $isDroite;

        return $this;
    }

    public function getIsGauche(): ?bool
    {
        return $this->isGauche;
    }

    public function setIsGauche(?bool $isGauche): self
    {
        $this->isGauche = $isGauche;

        return $this;
    }
}
