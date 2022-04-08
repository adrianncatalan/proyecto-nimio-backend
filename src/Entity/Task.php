<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "task")]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['Task:read']],
    denormalizationContext: ['groups' => ['Task:write']],/*
    collectionOperations: [
        'get' => ['security' => 'is_granted("ROLE_ADMIN")'],
        'post' => [
            'openapi_context' => [
                'tags' => ['Register']
            ]
        ],
    ],
    itemOperations: [
        'get' => ['security' => 'is_granted("ROLE_ADMIN") or is_granted("IS_SAME_USER", object)'],
        "put" => ["security" => "is_granted('ROLE_USER') or object.user == user"],
        'patch' => ['security' => 'is_granted("ROLE_ADMIN") or is_granted("IS_SAME_USER", object)'],
        'delete' => ["security" => "is_granted('ROLE_USER') or object.user == user"],
    ]*/
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'guid')]
    #[Groups(['Task:read'])]
    private $uuid;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['Task:read','Task:write'])]
    #[Assert\Length(min:'3', minMessage:'Debe de tener mas de 3 caracteres')]
    private $description;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['Task:read','Task:write'])]
    #[Assert\NotBlank(message: 'El campo no puede estar vacio')]
    private $startAt;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['Task:read','Task:write'])]
    #[Assert\NotBlank(message: 'El campo no puede estar vacio')]
    private $endAt;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['Task:read','Task:write'])]
    #[Assert\DateTime(message: 'Debe seleccionar una fecha valida'), Assert\NotBlank(message: 'El campo no puede estar vacio')]
    private $date;

    #[ORM\Column(type: 'integer')]
    #[Groups(['Task:read'])]
    private $duration;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"create")]
    #[Groups(['Task:read'])]
    private $createdAt;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"update")]
     private $updatedAt;

    #[ORM\Column(type:"datetime", nullable:true)]
     private $deletedAt;
 
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'tasks')]
    private $project;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    private $user;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTime $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}