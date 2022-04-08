<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompanyImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;

#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)]
#[ORM\Entity(repositoryClass: CompanyImageRepository::class)]
#[ApiResource]
class CompanyImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'guid')]
    private $uuid;

    #[ORM\Column(type: 'string', length: 255)]
    private $filePath;

    #[ORM\Column(type: 'string', length: 255)]
    private $alt;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"create")]
    private $createdAt;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"update")]
     private $updatedAt;

     #[ORM\Column(type:"datetime", nullable:true)]
     private $deletedAt;
 

    #[ORM\OneToOne(mappedBy: 'image', targetEntity: Company::class, cascade: ['persist', 'remove'])]
    private $company;


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

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }


    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        // unset the owning side of the relation if necessary
        if ($company === null && $this->company !== null) {
            $this->company->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($company !== null && $company->getImage() !== $this) {
            $company->setImage($this);
        }

        $this->company = $company;

        return $this;
    }
}
