<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Table(name: "project")]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['Project:read']],
    denormalizationContext: ['groups' => ['Project:write']]
)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'guid')]
    #[Groups(['Project:read'])]
    private $uuid;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['Project:read','Project:write'])]
    #[Assert\Length(min:'3', minMessage:'Debe de tener mas de 3 caracteres')]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['Project:read','Project:write'])]
    #[Assert\Length(min:'3', minMessage:'Debe de tener mas de 3 caracteres')]
    private $description;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'projects')]
    private $customer;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Task::class)]
    private $tasks;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"create")]
    private $createdAt;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"update")]
     private $updatedAt;

    #[ORM\Column(type:"datetime", nullable:true)]
     private $deletedAt;
 

    public function __construct()
    {
      
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->tasks;
    }

    public function setTask(?Task $tasks): self
    {
        $this->tasks = $tasks;

        return $this;
    }
}
