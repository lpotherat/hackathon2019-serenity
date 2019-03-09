<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OffreRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uuid_offre_idx", columns={"uuid"})})
 */
class Offre
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commerce;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $code_ean;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Aire", inversedBy="offres")
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCommerce(): ?string
    {
        return $this->commerce;
    }

    public function setCommerce(string $commerce): self
    {
        $this->commerce = $commerce;

        return $this;
    }

    public function getCodeEan(): ?string
    {
        return $this->code_ean;
    }

    public function setCodeEan(string $code_ean): self
    {
        $this->code_ean = $code_ean;

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
