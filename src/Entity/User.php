<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


//Para created_at y updated_at

// use Andante\SoftDeletableBundle\SoftDeletable\SoftDeletableInterface; //para deleted_at
// use Andante\SoftDeletableBundle\SoftDeletable\SoftDeletableTrait;  //para deleted_at

#[ORM\Table(name: "users")]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)] //Para el delete_at
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['User:read']],
    denormalizationContext: ['groups' => ['User:write']],
    collectionOperations: [
        'get' => ['security' => 'is_granted("ROLE_USER")','openapi_context' => [
            'tags' => ['Register']],
        ],
        'post' => [
            'openapi_context' => [
                'tags' => ['Register']
            ]
        ]
    ],
    itemOperations: [
        'get' => ['security' => 'is_granted("ROLE_ADMIN") or is_granted("IS_SAME_USER", object)'],
        'put' => ['security' => 'is_granted("ROLE_ADMIN") or is_granted("IS_SAME_USER", object)'],
        'patch' => ['security' => 'is_granted("ROLE_ADMIN") or is_granted("IS_SAME_USER", object)'],
        'delete' => ['security' => 'is_granted("ROLE_ADMIN") or is_granted("IS_SAME_USER", object)'],
    ]
)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'guid')]
    #[Groups(['User:read'])]
    private $uuid;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['User:read','User:write'])]
    #[Assert\Length(min:'3', minMessage:'Debe de tener mas de 3 caracteres')]
    private $name;

    #[ORM\Column(type: 'string', length: 40)]
    #[Groups(['User:read','User:write'])]
    #[Assert\Length(min:'3', minMessage:'Debe de tener mas de 3 caracteres')]
    private $surnames;

    #[ORM\Column(type: 'string', length: 60)]
    #[Groups(['User:read','User:write'])]
    // #[Assert\NotBlank(Message:'El campo no puede estar vacio'), Assert\Email(Message:'Debe tener formato email')]
    private $email;

    #[ORM\Column(type: 'boolean')]
    private $emailVerify = 0;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['User:write'])]
  //  #[Assert\NotBlank(Message:'El campo no puede estar vacio'), Assert\Length(min:'8', minMessage: 'Debe tener como minimo 8 caracteres'), Assert\Regex('(?i)^(?=.*[a-z])(?=.*\d).{8,}$', message: 'Debe tener almenos un numero y un caracter especial')]
    private $password;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Groups(['Company:read','Company:write'])]
    // #[Assert\Regex('/^\(0\)[0-9]*$', Message: 'El numero no tiene un formato valido')]
    private $phoneNumber;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class)]
    private $tasks;

    #[ORM\ManyToMany(targetEntity: UserRole::class, inversedBy: 'users')]
    #[ORM\JoinTable(name:"users_roles_rel")]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"role_id", referencedColumnName:"id")]
    #[Groups(['User:read','User:write'])]
    private $userRoles;
    
    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'users')]
    private $company;

    #[ORM\OneToOne(mappedBy: 'owner', targetEntity: UserImage::class, cascade: ['persist', 'remove'])]
    private $image;
    
    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"create")]
    #[Groups(['User:read'])]
    private $createdAt;

    #[ORM\Column(type:"datetime")]
    #[Gedmo\Timestampable(on:"update")]
     private $updatedAt;

    #[ORM\Column(type:"datetime", nullable:true)]
    private $deletedAt;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->uuid = Uuid::uuid4()->toString();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
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

    public function getSurnames(): ?string
    {
        return $this->surnames;
    }

    public function setSurnames(string $surnames): self
    {
        $this->surnames = $surnames;

        return $this;
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
     * @return mixed
     */
    public function getEmailVerify()
    {
        return $this->emailVerify;
    }

    /**
     * @param mixed $emailVerify
     * @return User
     */
    public function setEmailVerify($emailVerify)
    {
        $this->emailVerify = $emailVerify;
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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        $roles = array_map(fn(UserRole $role) => $role->getRole(), $this->userRoles->toArray());
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

   /**
     * @return Collection<int, UserRole>
     */
    public function getUserRoles():? Collection
     {
         return $this->userRoles;
     }

    public function addUserRole(UserRole $userRole): self
    {

         if (!$this->userRoles->contains($userRole)) {
             $this->userRoles[] = $userRole;
         }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): self
    {
        $this->userRoles->removeElement($userRole);

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

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUserTask($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUserTask() === $this) {
                $task->setUserTask(null);
            }
        }

        return $this;
    }

    public function getimage(): ?UserImage
    {
        return $this->image;
    }

    public function setimage(?UserImage $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getUserImage(): ?UserImage
    {
        return $this->userImage;
    }

    public function setUserImage(?UserImage $userImage): self
    {
        // unset the owning side of the relation if necessary
        if ($userImage === null && $this->userImage !== null) {
            $this->userImage->setOwner(null);
        }

        // set the owning side of the relation if necessary
        if ($userImage !== null && $userImage->getOwner() !== $this) {
            $userImage->setOwner($this);
        }

        $this->userImage = $userImage;

        return $this;
    }
}
