<?php
declare(strict_types=1);
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\FavoriteMovieController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="movie_user")
 */
#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    itemOperations: [
        'get',
        'put',
        'patch',
        'favorite' => [
            'method' => 'POST',
            'path' => '/posts/{id}/favorite',
            'controller' => FavoriteMovieController::class,
            'openapi_context' => [
                'summary' => 'Permet de rajouter un film en favori',
                'requestBoby' => [
                    'application/json' => [
                        'schema' => []
                    ]
                ]
            ]
        ]
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("user:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Groups({"user:read", "user:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * 
     * @Groups("user:read")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 

     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"user:read", "user:write"})
     */
    private $username;

    /**
     * @Groups("user:write")
     * 
     * @SerializedName("password")
     */
    private $plainPassword;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="users", cascade={"persist"})
     * @Groups({"user:read", "user:write"})
     */
    private $favorite;

    public function __construct()
    {
        $this->favorite = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }
    
    public function addFavorite(Movie $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
            $favorite->addUser($this);
        }

        return $this;
    }

    public function removeFavorite(Movie $favorite): self
    {
        $this->favorite->removeElement($favorite);

        return $this;
    }


}
