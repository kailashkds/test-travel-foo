<?php

namespace App\Entity;

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: DestinationRepository::class)]
#[Vich\Uploadable]
class Destination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['destination:list'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['destination:list'])]
    private string $name;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['destination:list'])]
    private string $description;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['destination:list'])]
    private float $price;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 10)]
    #[Groups(['destination:list'])]
    private string $duration;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $imageName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, length: 255, nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[Assert\File(
        maxSize: '2M',
        mimeTypes: [
            'image/jpeg',
            'image/png',
            'image/gif',
        ],
        maxSizeMessage: 'The image file should not be larger than 2 MB.',
        mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, GIF).',
    )]
    #[Vich\UploadableField(mapping: 'destination_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    // Getter and Setter for $id
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    // Getter and Setter for $name
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    // Getter and Setter for $description
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    // Getter and Setter for $price
    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    // Getter and Setter for $duration
    public function getDuration(): string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    // Getter and Setter for $imageName
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;
        return $this;
    }

    // Getter and Setter for $imageFile
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    #[Groups(['destination:list'])]
    public function getTimeStamp()
    {
        return $this->updatedAt->format('Y-m-d H:i:s');
    }
}
