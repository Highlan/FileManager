<?php

namespace App\Entity;

use App\Repository\FileRepository;
use App\Service\FileService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="files"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $format;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $origin_name;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

//    public function setOwner(?User $owner): self
//    {
//        $this->owner = $owner;
//
//        return $this;
//    }

    public function getSize(): ?int
    {
        return $this->size / 1000; //return as K
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function __construct(User $owner)
    {
        $this->owner = $owner;
    }

    public function getOriginName(): ?string
    {
        return $this->origin_name;
    }

    public function setOriginName(string $origin_name): self
    {
        $this->origin_name = $origin_name;

        return $this;
    }
}
