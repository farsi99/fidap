<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjetRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Projet
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budget;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeProjet", inversedBy="projets")
     */
    private $typeProjet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projets")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FichiersProjet", mappedBy="projet", orphanRemoval=true, cascade={"persist"})
     */
    private $fichiers;

    /**
     * 
     */
    private $projetfichiers;

    public function __construct()
    {
        $this->fichiers = new ArrayCollection();
    }

    /**
     * Cette méthode ajoute automatiquement la date de creation du projet
     * @ORM\PrePersist
     * @return void
     */
    public function ajoutDateCreation()
    {
        if (empty($this->dateCreation)) {
            $this->dateCreation = new \DateTime();
        }
        $this->etat = 'En attente';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTypeProjet(): ?TypeProjet
    {
        return $this->typeProjet;
    }

    public function setTypeProjet(?TypeProjet $typeProjet): self
    {
        $this->typeProjet = $typeProjet;

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

    /**
     * @return Collection|FichiersProjet[]
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(FichiersProjet $fichier): self
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers[] = $fichier;
            $fichier->setProjet($this);
        }

        return $this;
    }

    public function removeFichier(FichiersProjet $fichier): self
    {
        if ($this->fichiers->contains($fichier)) {
            $this->fichiers->removeElement($fichier);
            // set the owning side to null (unless already changed)
            if ($fichier->getProjet() === $this) {
                $fichier->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProjetfichiers()
    {
        return  $this->projetfichiers;
    }

    /**
     * @param mixed $projetfichiers
     * @return Projet
     */
    public function setProjetfichiers($projetfichiers): self
    {
        foreach ($projetfichiers as $fichier) {
            $fichiersProjet = new FichiersProjet();
            $fichiersProjet->setImageFile($fichier);
            $this->addFichier($fichiersProjet);
        }
        $this->projetfichiers = $projetfichiers;
        return $this;
    }
}
