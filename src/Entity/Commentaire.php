<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vote;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="commenaires")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inviter", inversedBy="commentaires")
     */
    private $inviter;

    public function __construct()
    {
        $this->article = new ArrayCollection();
        $this->inviter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(?string $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(?int $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getInviter(): ?Inviter
    {
        return $this->inviter;
    }

    public function setInviter(?Inviter $inviter): self
    {
        $this->inviter = $inviter;

        return $this;
    }
}
