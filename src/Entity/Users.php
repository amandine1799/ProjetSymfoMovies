<?php

namespace App\Entity;

use App\Entity\MediaUsers;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity(
 *         fields={"email"},
 *         message= "Cet Email est déjà utilisé",
 *                 {"username"},
 *          message= "Ce Username est déjà pris"

 * )
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *      message = "Cet email n'est pas valide", 
     *      checkMX = true
     * )
     */
    private $email;

     /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="6", minMessage="Votre mot de passe doit faire minimum 6 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas tapé le même mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="user")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediaUsers", mappedBy="users")
     */
    private $mediaUsers;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->mediaUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

public function eraseCredentials() {}

    public function getSalt() {}
    
    public function getRoles() {
        return ['ROLE_USER'];
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|mediaUsers[]
     */
    public function getMediaUsers(): Collection
    {
        return $this->mediaUsers;
    }

    public function getMediaUser(Media $media)
    {
        foreach($this->mediaUsers as $umedia){
            if($umedia->getMedia() == $media){
                return $umedia;
            }
        }
        return null;
    }

    public function addMediaUser(MediaUsers $mediaUser): self
    {
        if (!$this->mediaUsers->contains($mediaUser)) {
            $this->mediaUsers[] = $mediaUser;
            $mediaUser->setUsers($this);
        }

        return $this;
    }

    public function removeMediaUser(MediaUsers $mediaUser): self
    {
        if ($this->mediaUsers->contains($mediaUser)) {
            $this->mediaUsers->removeElement($mediaUser);
            // set the owning side to null (unless already changed)
            if ($mediaUser->getUsers() === $this) {
                $mediaUser->setUsers(null);
            }
        }

        return $this;
    }
}