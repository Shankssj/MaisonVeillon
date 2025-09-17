<?php

namespace App\Entity;

use App\Repository\PiecesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: PiecesRepository::class)]
class Pieces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_piece = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_piece = null;

    #[ORM\Column(length: 9999, nullable: true)]
    private ?string $description_piece = null;

    #[ORM\OneToMany(mappedBy: 'piece', targetEntity: Photo::class, cascade: ['persist', 'remove'])]
    private Collection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_piece;
    }

    public function getNomPiece(): ?string
    {
        return $this->nom_piece;
    }

    public function setNomPiece(?string $nom_piece): static
    {
        $this->nom_piece = $nom_piece;

        return $this;
    }

    public function getDescriptionPiece(): ?string
    {
        return $this->description_piece;
    }

    public function setDescriptionPiece(?string $description_piece): static
    {
        $this->description_piece = $description_piece;

        return $this;
    }


    public function getPhotos(): Collection
    {
        return $this->photos;
    }


    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setPiece($this);
        }


        return $this;
    }


    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            if ($photo->getPiece() === $this) {
                $photo->setPiece(null);
            }
        }


        return $this;
    }
}
