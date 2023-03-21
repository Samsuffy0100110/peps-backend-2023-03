<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email', 'Cet email existe déjà !')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user'])]
    #[Assert\Email(message: 'L\'email "{{ value }}" n\'est pas valide.')]
    #[Assert\NotBlank(message: 'L\'email est obligatoire.')]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user'])]
    #[Assert\NotBlank()]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user'])]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 8,
        minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/',
        message: 'Le mot de passe doit contenir au moins une minuscule,
        une majuscule, un chiffre et un caractère spécial.'
    )]
    private ?string $password = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['user'])]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le prénom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le prénom doit faire au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ -]+$/i',
        message: 'Le prénom ne doit contenir que des lettres, des espaces et des tirets.'
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['user'])]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom doit faire au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ -]+$/i',
        message: 'Le nom ne doit contenir que des lettres, des espaces et des tirets.'
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user'])]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'L\'adresse doit faire au moins {{ limit }} caractères.',
        maxMessage: 'L\'adresse doit faire au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ0-9 ,.-]+$/i',
        message: 'L\'adresse ne doit contenir que des lettres, des chiffres, des espaces, des virgules et des tirets.'
    )]
    private ?string $address = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['user'])]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le code postal doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le code postal doit faire au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[0-9]{5}$/',
        message: 'Le code postal doit contenir 5 chiffres.'
    )]
    private ?string $zipcode = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['user'])]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'La ville doit faire au moins {{ limit }} caractères.',
        maxMessage: 'La ville doit faire au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ0-9 -]+$/i',
        message: 'La ville ne doit contenir que des lettres et des chiffres,
        des espaces et des tirets.'
    )]
    private ?string $city = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
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

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return User
     */
    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     * @return User
     */
    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return User
     */
    public function setAddress(?string $address): User
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    /**
     * @param string|null $zipcode
     * @return User
     */
    public function setZipcode(?string $zipcode): User
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return User
     */
    public function setCity(?string $city): User
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
