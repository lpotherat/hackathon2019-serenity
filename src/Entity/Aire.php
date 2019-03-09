<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AireRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uuid_aire_idx", columns={"uuid"})})
 */
class Aire
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
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $lat;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $lgt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tag", mappedBy="aire")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offre", mappedBy="aire", orphanRemoval=true)
     */
    private $offres;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->offres = new ArrayCollection();
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLgt()
    {
        return $this->lgt;
    }

    public function setLgt($lgt): self
    {
        $this->lgt = $lgt;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setAire($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            // set the owning side to null (unless already changed)
            if ($tag->getAire() === $this) {
                $tag->setAire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setAire($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->contains($offre)) {
            $this->offres->removeElement($offre);
            // set the owning side to null (unless already changed)
            if ($offre->getAire() === $this) {
                $offre->setAire(null);
            }
        }

        return $this;
    }
}
