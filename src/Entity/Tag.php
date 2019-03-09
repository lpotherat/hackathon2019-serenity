<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uuid_tag_idx", columns={"uuid"})})
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="id")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $data;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $numserie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Aire", inversedBy="tags")
     * @ORM\JoinColumn(nullable=false)
     */
    private $aire;

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

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getNumserie(): ?string
    {
        return $this->numserie;
    }

    public function setNumserie(string $numserie): self
    {
        $this->numserie = $numserie;

        return $this;
    }

    public function getAire(): ?Aire
    {
        return $this->aire;
    }

    public function setAire(?Aire $aire): self
    {
        $this->aire = $aire;

        return $this;
    }
}
