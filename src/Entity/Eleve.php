<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EleveRepository::class)
 */
class Eleve
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
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="eleve", orphanRemoval=true)
     */
    private $notes;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\OneToMany(targetEntity=Abscence::class, mappedBy="eleve", orphanRemoval=true)
     */
    private $abscences;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Annee::class, inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ancienLycee;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rgpd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRedoublant;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $frais;

    /**
     * @ORM\OneToMany(targetEntity=Payement::class, mappedBy="eleve", orphanRemoval=true)
     */
    private $payements;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ape;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->abscences = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->payements = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom. " " . $this->prenom ."-". $this->annee->getNom();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setEleve($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEleve() === $this) {
                $note->setEleve(null);
            }
        }

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * @return Collection<int, Abscence>
     */
    public function getAbscences(): Collection
    {
        return $this->abscences;
    }

    public function addAbscence(Abscence $abscence): self
    {
        if (!$this->abscences->contains($abscence)) {
            $this->abscences[] = $abscence;
            $abscence->setEleve($this);
        }

        return $this;
    }

    public function removeAbscence(Abscence $abscence): self
    {
        if ($this->abscences->removeElement($abscence)) {
            // set the owning side to null (unless already changed)
            if ($abscence->getEleve() === $this) {
                $abscence->setEleve(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }

    public function setAnnee(?Annee $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getAncienLycee(): ?string
    {
        return $this->ancienLycee;
    }

    public function setAncienLycee(?string $ancienLycee): self
    {
        $this->ancienLycee = $ancienLycee;

        return $this;
    }

    public function isRgpd(): ?bool
    {
        return $this->rgpd;
    }

    public function setRgpd(?bool $rgpd): self
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    public function isIsRedoublant(): ?bool
    {
        return $this->isRedoublant;
    }

    public function setIsRedoublant(?bool $isRedoublant): self
    {
        $this->isRedoublant = $isRedoublant;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(?int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    /**
     * @return Collection<int, Payement>
     */
    public function getPayements(): Collection
    {
        return $this->payements;
    }

    public function addPayement(Payement $payement): self
    {
        if (!$this->payements->contains($payement)) {
            $this->payements[] = $payement;
            $payement->setEleve($this);
        }

        return $this;
    }

    public function removePayement(Payement $payement): self
    {
        if ($this->payements->removeElement($payement)) {
            // set the owning side to null (unless already changed)
            if ($payement->getEleve() === $this) {
                $payement->setEleve(null);
            }
        }

        return $this;
    }

    public function getApe(): ?int
    {
        return $this->ape;
    }

    public function setApe(?int $ape): self
    {
        $this->ape = $ape;

        return $this;
    }
}
