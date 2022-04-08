<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "customer")]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)]
#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['Customer:read']],
    denormalizationContext: ['groups' => ['Customer:write']]
)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'guid')]
    #[Groups(['Customer:read'])]
    private $uuid;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['Customer:read','Customer:write'])]
    #[Assert\NotBlank(message: 'El campo no puede estar vacio')]
    private $name;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Project::class)]
    private $projects;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'customers')]
    private $company;

    #[ORM\OneToOne(inversedBy: 'customer', targetEntity: CustomerImage::class, cascade: ['persist', 'remove'])]
    private $image;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"create")]
    #[Groups(['Customer:read'])]
    private $createdAt;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"update")]
     private $updatedAt;

    #[ORM\Column(type:"datetime", nullable:true)]
     private $deletedAt;
 

    public function __construct()
    {
        $this->customer = new ArrayCollection();
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

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?CustomerImage
    {
        return $this->image;
    }

    public function setImage(?CustomerImage $image): self
    {
        $this->image = $image;

        return $this;
    }



    /**
     * @return Collection<int, Project>
     */
    public function getCustomer(): Collection
    {
        return $this->customer;
    }

    public function addProjects(Project $projects): self
    {
        if (!$this->projects->contains($projects)) {
            $this->projects[] = $projects;
            $projects->setCustomer($this);
        }

        return $this;
    }

    public function removeProjects(Project $projects): self
    {
        if ($this->customer->removeElement($projects)) {
            // set the owning side to null (unless already changed)
            if ($projects->getCustomer() === $this) {
                $projects->setCustomer(null);
            }
        }

        return $this;
    }


    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

}
