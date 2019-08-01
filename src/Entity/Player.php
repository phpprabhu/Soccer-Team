<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;
use App\Util\RepositoryHelper;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * Repository helper traits
     */
    use RepositoryHelper;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="players")
     */
    private $team;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="The First name should not be blank")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @Assert\NotBlank(message="The Last name should not be blank")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @Assert\NotBlank(message="Image URI should not be blank")
     * @Assert\Url(message="Logo URL should be valid URL")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $imageURI;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName = null): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName = null): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getImageURI(): ?string
    {
        return $this->imageURI;
    }

    public function setImageURI(string $imageURI = null): self
    {
        $this->imageURI = $imageURI;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team = null): self
    {
        $this->team = $team;

        return $this;
    }
}
