<?php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Media\CreateUserImageAction;
use App\Controller\UserImageController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity]
#[ApiResource(
    iri: 'http://schema.org/MediaObject',
    normalizationContext: ['groups' => ['UserImage:read']],
    itemOperations: ['get' =>
        ['security' => 'is_granted("ROLE_USER") or object.owner == user',
        'controller' => UserImageController::class]],
    collectionOperations: [
        'get',
        'post' => [
            'controller' => CreateUserImageAction::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'UserImage:write'],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'alt' => [
                                        'type' => 'string'
                                    ],
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                    'owner' => [
                                        'type' => 'string'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]
)]
class UserImage
{

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ApiProperty(iri: 'http://schema.org/contentUrl')]
    #[Groups(['UserImage:read'])]
    public ?string $contentUrl = null;

    /**
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="filePath")
     */
    #[Assert\NotNull(groups: ['UserImage:write'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    public ?string $filePath = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['UserImage:read','UserImage:write'])]
    private $alt;

    #[ORM\OneToOne(inversedBy: 'image', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[Groups(['UserImage:read','UserImage:write'])]
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     * @return UserImage
     */
    public function setOwner(User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }


    const expire = 3600;

    #[Groups(['UserImage:read'])]
    public function getExp(): string
    {
        $expire = time() + $this::expire;
        $payload = "";
        $hash = $expire . ':' . hash_hmac('sha1', $payload, $expire);
        return $hash;
    }
}
