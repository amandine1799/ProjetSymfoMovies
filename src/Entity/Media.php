<?php

namespace App\Entity;

use App\Entity\MediaUsers;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 * @UniqueEntity(fields= {"title"}, message="Ce film a déjà été ajouté")
 */
class Media
{

    const FILM = 1;
    const SERIE = 2;

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
     * @ORM\Column(type="date")
     */
    private $released;

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

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $trailer;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="media")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediaUsers", mappedBy="media")
     */
    private $mediaUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Likes", mappedBy="media", orphanRemoval=true)
     */
    private $likes;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->mediaUsers = new ArrayCollection();
        $this->likes = new ArrayCollection();
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

    public function getReleased(): ?\DateTimeInterface
    {
        return $this->released;
    }

    public function setReleased(\DateTimeInterface $released): self
    {
        $this->released = $released;

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

    public function getTrailer(): ?string
    {
        return $this->trailer;
    }

    public function setTrailer(string $trailer): self
    {
        $this->trailer = $trailer;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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
            $review->setMedia($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getMedia() === $this) {
                $review->setMedia(null);
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

    public function addMediaUser(MediaUsers $mediaUser): self
    {
        if (!$this->mediaUsers->contains($mediaUser)) {
            $this->mediaUsers[] = $mediaUser;
            $mediaUser->setMedia($this);
        }

        return $this;
    }

    public function removeMediaUser(MediaUsers $mediaUser): self
    {
        if ($this->mediaUsers->contains($mediaUser)) {
            $this->mediaUsers->removeElement($mediaUser);
            // set the owning side to null (unless already changed)
            if ($mediaUser->getMedia() === $this) {
                $mediaUser->setMedia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Likes[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Likes $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setMedia($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getMedia() === $this) {
                $like->setMedia(null);
            }
        }

        return $this;
    }
}