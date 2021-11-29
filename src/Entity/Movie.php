<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 * @ApiResource(
 *     formats={"json"},
 *     normalizationContext={"groups"={"movie:read"}},
 *     denormalizationContext={"groups"={"movie:write"}}
 * )
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("movie:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movie:read", "movie:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movie:read", "movie:write"})
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movie:read", "movie:write"})
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movie:read", "movie:write"})
     */
    private $img;

    /**
     * @ORM\Column(type="text")
     * @Groups({"movie:read", "movie:write"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favorite", cascade={"persist"})
     * @Groups("movie:read")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(string $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }

 
}
