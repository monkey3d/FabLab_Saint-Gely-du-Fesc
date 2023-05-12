<?php

namespace App\Entity\DocumentManagement;

use App\Repository\DocumentManagement\DocumentRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Document
{
    public const MAX_ITEMS_PER_PAGE = 24;
    
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column]
    private ?int $numberViews = null;

    #[ORM\ManyToMany(targetEntity: Author::class, mappedBy: 'documents')]
    private Collection $authors;
    
    //----- début partie Vich -------------------------------------------------------------------------------------------------------------------
    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'document_content', fileNameProperty: 'documentName', size: 'documentSize', mimeType: 'documentMimeType')]
    private ?File $documentFile = null;
    
    #[ORM\Column(length: 255)]
    private ?string $documentName = null;
    
    #[ORM\Column(nullable: true)]
    private ?int $documentSize = null;
    
    #[ORM\Column(length: 255)]
    private ?string $documentMimeType = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'documents')]
    private Collection $categories;
    
     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $documentFile
     */
    public function setDocumentFile(?File $documentFile = null): void
    {
        $this->documentFile = $documentFile;
        
        if (null !== $documentFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    
    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }
    
    public function setDocumentName(?string $documentName): void
    {
        $this->documentName = $documentName;
    }
    
    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }
    
    public function setDocumentSize(?int $documentSize): void
    {
        $this->documentSize = $documentSize;
    }
    
    public function getDocumentSize(): ?int
    {
        return $this->documentSize;
    }
    
    public function setDocumentMimeType(?string $documentMimeType): void
    {
        $this->documentMimeType = $documentMimeType;
    }
    
    public function getDocumentMimeTYpe(): ?string
    {
        return $this->documentMimeType;
    }
    
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    //----- fin partie Vich -----------------------------------------------------------------------------------
    
    public function __construct()
    {
        $this->numberViews = 0;
        $this->authors = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getNumberViews(): ?int
    {
        return $this->numberViews;
    }

    public function setNumberViews(int $numberViews): self
    {
        $this->numberViews = $numberViews;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addDocument($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            $author->removeDocument($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addDocument($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeDocument($this);
        }

        return $this;
    }
}
