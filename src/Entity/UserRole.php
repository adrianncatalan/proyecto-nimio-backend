<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "userRole")]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)]
#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['UserRole:read']],
    denormalizationContext: ['groups' => ['UserRole:write']]
)]
class UserRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'guid')]
    #[Groups(['UserRole:read'])]
    private $uuid;

    #[ORM\Column(type: 'string', length: 25)]
    #[Groups(['UserRole:read','UserRole:write'])]
    #[Assert\Length(min:'3', minMessage:'Debe de tener mas de 3 caracteres')]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['UserRole:read'])]
    private $role;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['UserRole:read','UserRole:write'])]
    #[Assert\Length(min:'3', minMessage:'Debe de tener mas de 3 caracteres')]
    private $description;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"create")]
    #[Groups(['UserRole:read'])]
    private $createdAt;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"update")]
     private $updatedAt;

     #[ORM\Column(type:"datetime", nullable:true)]
     private $deletedAt;
 


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return UserRole
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // public function getRoleCode(): ?string
    // {
    //     return $this->roleCode;
    // }

    // public function setRoleCode(string $roleCode): self
    // {
    //     $this->roleCode = $roleCode;

    //     return $this;
    // }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
