<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_photo = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(name: "id_piece", referencedColumnName:"id_piece", nullable: false, onDelete: 'CASCADE')]
    private ?Pieces $piece = null;

    #[ORM\Column(length: 9999, nullable: true)]
    private ?string $lien_photo = null;

    public function getId(): ?int
    {
        return $this->id_photo;
    }

    public function getLienPhoto(): ?string
    {
        return $this->lien_photo;
    }

    public function setLienPhoto(?string $lien_photo): static
    {
        $this->lien_photo = $lien_photo;

        return $this;
    }

    public function getPiece(): ?Pieces
    {
        return $this->piece;
    }


    public function setPiece(?Pieces $piece): self
    {
        $this->piece = $piece;


        return $this;
    }
}
