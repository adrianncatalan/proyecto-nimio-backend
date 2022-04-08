<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo; //Para created_at y updated_at
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "company")]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)]
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['Company:read']],
    denormalizationContext: ['groups' => ['Company:write']]
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'guid')]
    #[Groups(['Company:read'])]
    private $uuid;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['Company:read','Company:write'])]
    #[Assert\Length(min:'3', minMessage: "Debe de tener mas de 3 caracteres")]
    private $name;

    #[ORM\Column(type: 'string', length: 10)]
    #[Groups(['Company:read'])]
    #[Assert\NotBlank(message:'El campo no puede estar vacio')]
    private $taxCode;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['Company:read','Company:write'])]
    #[Assert\Regex('/^\(0\)[0-9]*$', message: 'El numero no tiene un formato valido')]
    private $phoneNumber;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"create")]
    #[Groups(['Company:read'])]
    private $createdAt;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"update")]
     private $updatedAt;

     #[ORM\Column(type:"datetime", nullable:true)]
     private $deletedAt;

    #[ORM\OneToOne(inversedBy: 'company', targetEntity: CompanyImage::class, cascade: ['persist', 'remove'])]
    private $image;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Customer::class)]
    private $customers;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class)]
    private $users;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
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


    public function getImage(): ?CompanyImage
    {
        return $this->image;
    }

    public function setImage(?CompanyImage $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setCompany($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getCompany() === $this) {
                $customer->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }

    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    public function setTaxCode(string $taxCode): self
    {
        $this->taxCode = $taxCode;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    
}
