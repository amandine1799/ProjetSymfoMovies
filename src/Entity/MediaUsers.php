<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaUsersRepository")
 */
class MediaUsers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wishList;

    /**
     * @ORM\Column(type="boolean")
     */
    private $haveSeen;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", inversedBy="mediaUsers")
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="mediaUsers")
     */
    private $users;

    public function __construct()
    {
        $this->wishList = false;
        $this->haveSeen = false; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWishList(): ?bool
    {
        return $this->wishList;
    }

    public function setWishList(bool $wishList): self
    {
        $this->wishList = $wishList;

        return $this;
    }

    public function getHaveSeen(): ?bool
    {
        return $this->haveSeen;
    }

    public function setHaveSeen(bool $haveSeen): self
    {
        $this->haveSeen = $haveSeen;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }
}
