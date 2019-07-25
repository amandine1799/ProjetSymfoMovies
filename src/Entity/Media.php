<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $released_year;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $poster;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Genres", inversedBy="media")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genres;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Actors", inversedBy="media")
     */
    private $actors;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getReleasedYear(): ?string
    {
        return $this->released_year;
    }

    public function setReleasedYear(string $released_year): self
    {
        $this->released_year = $released_year;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getGenres(): ?Genres
    {
        return $this->genres;
    }

    public function setGenres(?Genres $genres): self
    {
        $this->genres = $genres;

        return $this;
    }

    /**
     * @return Collection|Actors[]
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actors $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
        }

        return $this;
    }

    public function removeActor(Actors $actor): self
    {
        if ($this->actors->contains($actor)) {
            $this->actors->removeElement($actor);
        }

        return $this;
    }
}
