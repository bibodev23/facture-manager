<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
#[Vich\Uploadable]
class Delivery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'deliveries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cmr = null;

    #[Vich\UploadableField(mapping: 'cmrs', fileNameProperty: 'cmr')]
    private ?File $cmrFile = null;

    #[ORM\ManyToOne(inversedBy: 'deliveries')]
    private ?Invoice $invoice = null;

    #[ORM\ManyToOne(inversedBy: 'deliveries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\Column]
    private ?bool $invoiced = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otherDocument = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCmr(): ?string
    {
        return $this->cmr;
    }

    public function setCmr(string $cmr): static
    {
        $this->cmr = $cmr;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function isInvoiced(): ?bool
    {
        return $this->invoiced;
    }

    public function setInvoiced(bool $invoiced): static
    {
        $this->invoiced = $invoiced;

        return $this;
    }

    public function getOtherDocument(): ?string
    {
        return $this->otherDocument;
    }

    public function setOtherDocument(?string $otherDocument): static
    {
        $this->otherDocument = $otherDocument;

        return $this;
    }

    /**
     * Get the value of cmrFile
     */
    public function getCmrFile(): ?File
    {
        return $this->cmrFile;
    }

    /**
     * Set the value of cmrFile
     */
    public function setCmrFile(?File $cmrFile): void
    {
        $this->cmrFile = $cmrFile;
    }
}
