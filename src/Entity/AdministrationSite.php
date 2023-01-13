<?php

namespace App\Entity;

use App\Repository\AdministrationSiteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdministrationSiteRepository::class)
 */
class AdministrationSite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $soustitre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fonctionnement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sousfonctionnement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $soustitre1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre2;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $soustitre2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre3;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $soustitre3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sousdescription1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sousdescription2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $localisation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $quiSommeNous;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSoustitre(): ?string
    {
        return $this->soustitre;
    }

    public function setSoustitre(?string $soustitre): self
    {
        $this->soustitre = $soustitre;

        return $this;
    }

    public function getFonctionnement(): ?string
    {
        return $this->fonctionnement;
    }

    public function setFonctionnement(?string $fonctionnement): self
    {
        $this->fonctionnement = $fonctionnement;

        return $this;
    }

    public function getSousfonctionnement(): ?string
    {
        return $this->sousfonctionnement;
    }

    public function setSousfonctionnement(?string $sousfonctionnement): self
    {
        $this->sousfonctionnement = $sousfonctionnement;

        return $this;
    }

    public function getTitre1(): ?string
    {
        return $this->titre1;
    }

    public function setTitre1(?string $titre1): self
    {
        $this->titre1 = $titre1;

        return $this;
    }

    public function getSoustitre1(): ?string
    {
        return $this->soustitre1;
    }

    public function setSoustitre1(?string $soustitre1): self
    {
        $this->soustitre1 = $soustitre1;

        return $this;
    }

    public function getTitre2(): ?string
    {
        return $this->titre2;
    }

    public function setTitre2(?string $titre2): self
    {
        $this->titre2 = $titre2;

        return $this;
    }

    public function getSoustitre2(): ?string
    {
        return $this->soustitre2;
    }

    public function setSoustitre2(string $soustitre2): self
    {
        $this->soustitre2 = $soustitre2;

        return $this;
    }

    public function getTitre3(): ?string
    {
        return $this->titre3;
    }

    public function setTitre3(?string $titre3): self
    {
        $this->titre3 = $titre3;

        return $this;
    }

    public function getSoustitre3(): ?string
    {
        return $this->soustitre3;
    }

    public function setSoustitre3(?string $soustitre3): self
    {
        $this->soustitre3 = $soustitre3;

        return $this;
    }

    public function getDescription1(): ?string
    {
        return $this->description1;
    }

    public function setDescription1(?string $description1): self
    {
        $this->description1 = $description1;

        return $this;
    }

    public function getSousdescription1(): ?string
    {
        return $this->sousdescription1;
    }

    public function setSousdescription1(?string $sousdescription1): self
    {
        $this->sousdescription1 = $sousdescription1;

        return $this;
    }

    public function getDescription2(): ?string
    {
        return $this->description2;
    }

    public function setDescription2(?string $description2): self
    {
        $this->description2 = $description2;

        return $this;
    }

    public function getSousdescription2(): ?string
    {
        return $this->sousdescription2;
    }

    public function setSousdescription2(?string $sousdescription2): self
    {
        $this->sousdescription2 = $sousdescription2;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getQuiSommeNous(): ?string
    {
        return $this->quiSommeNous;
    }

    public function setQuiSommeNous(?string $quiSommeNous): self
    {
        $this->quiSommeNous = $quiSommeNous;

        return $this;
    }
}
