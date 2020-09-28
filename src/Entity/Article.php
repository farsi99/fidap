<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 */
class Article
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resume;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $format;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeArticle", inversedBy="articles")
     */
    private $typeArticle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="Articles")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="article")
     */
    private $commenaires;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $menu;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $AffichageMenu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slugMenu;


    /**
     * @Vich\UploadableField(mapping="fichier_article", fileNameProperty="thumbnail")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $publication;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $MenuParent;



    public function __construct()
    {
        $this->commenaires = new ArrayCollection();
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

    /**
     * Cette méthode initialise un slug avant l'ajout de l'article
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function initialisationSlug()
    {
        if (empty($this->slug)) {
            $slug = new Slugify();
            $this->slug = $slug->slugify($this->titre);
        }
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(?string $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->meta_title;
    }

    public function setMetaTitle(?string $meta_title): self
    {
        $this->meta_title = $meta_title;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): self
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->dateModification = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }



    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getTypeArticle(): ?TypeArticle
    {
        return $this->typeArticle;
    }

    public function setTypeArticle(?TypeArticle $typeArticle): self
    {
        $this->typeArticle = $typeArticle;

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
     * @return Collection|Commentaire[]
     */
    public function getCommenaires(): Collection
    {
        return $this->commenaires;
    }

    public function addCommenaire(Commentaire $commenaire): self
    {
        if (!$this->commenaires->contains($commenaire)) {
            $this->commenaires[] = $commenaire;
            $commenaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommenaire(Commentaire $commenaire): self
    {
        if ($this->commenaires->contains($commenaire)) {
            $this->commenaires->removeElement($commenaire);
            // set the owning side to null (unless already changed)
            if ($commenaire->getArticle() === $this) {
                $commenaire->setArticle(null);
            }
        }

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }


    public function getMenu(): ?string
    {
        return $this->menu;
    }

    public function setMenu(?string $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getAffichageMenu(): ?bool
    {
        return $this->AffichageMenu;
    }

    public function setAffichageMenu(?bool $AffichageMenu): self
    {
        $this->AffichageMenu = $AffichageMenu;

        return $this;
    }

    /**
     * Cette méthode permet de traiter les menus
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */

    public function initialisationMenu()
    {

        $slugify = new Slugify();
        $this->slugMenu = $slugify->slugify($this->menu);
    }
    public function getSlugMenu(): ?string
    {
        return $this->slugMenu;
    }

    public function setSlugMenu(?string $slugMenu): self
    {
        $this->slugMenu = $slugMenu;

        return $this;
    }

    public function getPublication(): ?bool
    {
        return $this->publication;
    }

    public function setPublication(?bool $publication): self
    {
        $this->publication = $publication;

        return $this;
    }

    public function getMenuParent(): ?string
    {
        return $this->MenuParent;
    }

    public function setMenuParent(?string $MenuParent): self
    {
        $this->MenuParent = $MenuParent;

        return $this;
    }
}
