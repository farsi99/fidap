<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use function PHPSTORM_META\map;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity( fields={"email"},
 *     groups={"registration"},
 *     message="Cet email existe déjà, merci de vous connecter!")
 */
class User implements UserInterface
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
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseignez un email valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $raisonSocial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lienUrl;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkdin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user")
     */
    private $Articles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeUser", inversedBy="users")
     */
    private $typeUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Projet", mappedBy="user")
     */
    private $projets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="user")
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motpasse;

    /**
     * @Assert\EqualTo(propertyPath="motpasse", message="Veuillez saisir le même mot de passe!")
     */
    public $confpass;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @Vich\UploadableField(mapping="profil_user", fileNameProperty="photo")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetPassword;


    public function __construct()
    {
        $this->Articles = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    /**
     * Initialisation du slug
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function InitialisationSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName . ' ' . $this->lastName);
        }
    }

    public function getFullName()
    {
        return ucfirst($this->getLastName()) . ' ' . strtoupper($this->getFirstName());
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(?string $raisonSocial): self
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(?string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getLienUrl(): ?string
    {
        return $this->lienUrl;
    }

    public function setLienUrl(?string $lienUrl): self
    {
        $this->lienUrl = $lienUrl;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getLinkdin(): ?string
    {
        return $this->linkdin;
    }

    public function setLinkdin(?string $linkdin): self
    {
        $this->linkdin = $linkdin;

        return $this;
    }

    public function getSkype(): ?string
    {
        return $this->skype;
    }

    public function setSkype(?string $skype): self
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->Articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->Articles->contains($article)) {
            $this->Articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->Articles->contains($article)) {
            $this->Articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    public function getTypeUser(): ?TypeUser
    {
        return $this->typeUser;
    }

    public function setTypeUser(?TypeUser $typeUser): self
    {
        $this->typeUser = $typeUser;

        return $this;
    }



    /**
     * @return Collection|Projet[]
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets[] = $projet;
            $projet->setUser($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->contains($projet)) {
            $this->projets->removeElement($projet);
            // set the owning side to null (unless already changed)
            if ($projet->getUser() === $this) {
                $projet->setUser(null);
            }
        }

        return $this;
    }



    public function getMotpasse(): ?string
    {
        return $this->motpasse;
    }

    public function setMotpasse(?string $motpasse): self
    {
        $this->motpasse = $motpasse;

        return $this;
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


    public function getRoles()
    {
        $roles = $this->userRoles->map(function ($role) {
            return $role->getTitre();
        })->toArray();

        $roles[] = 'ROLE_USER';
        return $roles;
    }

    public function getPassword()
    {
        return $this->motpasse;
    }

    public function getSalt()
    { }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    { }


    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    public function getResetPassword(): ?string
    {
        return $this->resetPassword;
    }

    public function setResetPassword(?string $resetPassword): self
    {
        $this->resetPassword = $resetPassword;

        return $this;
    }
}
