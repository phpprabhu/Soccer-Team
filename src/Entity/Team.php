<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Player;
use Swagger\Annotations as SWG;
use App\Util\RepositoryHelper;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="I think another team is having same name!"
 * )
 */
class Team
{
    /**
     * Repository helper traits
     */
    use RepositoryHelper;

    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="team", fetch="LAZY")
     */
    private $players;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @SWG\Property(description="The unique identifier of the user.")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="The Username should not be blank")
     *
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(type="string", maxLength=255)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="The Logo URL should not be blank")
     * @Assert\Url(message="Logo URL should be valid URL")
     *
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(type="string", maxLength=255)
     */
    private $logo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo = null): self
    {
        $this->logo = $logo;

        return $this;
    }


    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    /**
     * Generates the magic method
     *
     */
    public function __toString()
    {
        return $this->name;
    }
}
