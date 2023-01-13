<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity=Eleve::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity=Trimestre::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trimestre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $devoir;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSend;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getTrimestre(): ?Trimestre
    {
        return $this->trimestre;
    }

    public function setTrimestre(?Trimestre $trimestre): self
    {
        $this->trimestre = $trimestre;

        return $this;
    }

    public function getDevoir(): ?string
    {
        return $this->devoir;
    }

    public function setDevoir(?string $devoir): self
    {
        $this->devoir = $devoir;

        return $this;
    }

    public function isIsSend(): ?bool
    {
        return $this->isSend;
    }

    public function setIsSend(?bool $isSend): self
    {
        $this->isSend = $isSend;

        return $this;
    }
}
