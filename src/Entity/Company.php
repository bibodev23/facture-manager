<?php

namespace App\Entity;

use App\Enum\LegalForm;
use App\Enum\ThemeSelection;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'company', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 14, unique: true, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{14}$/', message: 'Le SIRET doit contenir exactement 14 chiffres.')]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $tvaNumber = null;

    #[ORM\Column(nullable: true)]
    private ?float $tvaRate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $iban = null;

    /**
     * @var Collection<int, Customer>
     */
    #[ORM\OneToMany(targetEntity: Customer::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $customers;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $invoices;

    /**
     * @var Collection<int, Delivery>
     */
    #[ORM\OneToMany(targetEntity: Delivery::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $deliveries;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bic = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siren = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $invoicePrimaryColor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $invoiceTextColor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[Vich\UploadableField(mapping: 'logos', fileNameProperty: 'logo')]
    private ?File $logoFile = null;

    #[ORM\Column(enumType: ThemeSelection::class, type: 'string' ,nullable: true)]
    private ?ThemeSelection $themeSelection = null;

    #[ORM\Column( enumType: LegalForm::class, type: 'string', nullable: true)]
    private ?LegalForm $legalForm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shareCapital = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rcs = null;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
    }
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function normalize(): void
    {
        // enlève espaces, tirets ; garde uniquement les chiffres
        $this->siret = preg_replace('/\D+/', '', $this->siret ?? '');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getTvaNumber(): ?string
    {
        return $this->tvaNumber;
    }

    public function setTvaNumber(string $tvaNumber): static
    {
        $this->tvaNumber = $tvaNumber;

        return $this;
    }

    public function getTvaRate(): ?float
    {
        return $this->tvaRate;
    }

    public function setTvaRate(float $tvaRate): static
    {
        $this->tvaRate = $tvaRate;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }
    public function isComplete(): bool
    {
        return (
            $this->siret != null
            && $this->tvaNumber != null
            && $this->address1 != null
            && $this->postalCode != null
            && $this->city != null
        );
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): static
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setCompany($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): static
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getCompany() === $this) {
                $customer->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setCompany($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getCompany() === $this) {
                $invoice->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Delivery>
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Delivery $delivery): static
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries->add($delivery);
            $delivery->setCompany($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): static
    {
        if ($this->deliveries->removeElement($delivery)) {
            // set the owning side to null (unless already changed)
            if ($delivery->getCompany() === $this) {
                $delivery->setCompany(null);
            }
        }

        return $this;
    }

    public function getDeliveriesNotInvoiced(): Collection
    {
        return $this->deliveries->filter(fn(Delivery $delivery) => !$delivery->isInvoiced());
    }
    public function getDeliveriesInvoiced(): Collection
    {
        return $this->deliveries->filter(fn(Delivery $delivery) => $delivery->isInvoiced());
    }

    public function getDeliveriesInvoicesAmount(): float
    {
        return $this->deliveries->filter(fn(Delivery $delivery) => $delivery->isInvoiced())
            ->map(fn(Delivery $delivery) => $delivery->getAmount())
            ->reduce(fn(float $carry, float $amount) => $carry + $amount, 0);
    }

    public function getTotalAmountDeliveries(): float
    {
        return $this->deliveries->map(fn(Delivery $delivery) => $delivery->getAmount())
            ->reduce(fn(float $carry, float $amount) => $carry + $amount, 0);
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function setBic(?string $bic): static
    {
        $this->bic = $bic;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    public function getInvoicePrimaryColor(): ?string
    {
        return $this->invoicePrimaryColor;
    }

    public function setInvoicePrimaryColor(?string $invoicePrimaryColor): static
    {
        $this->invoicePrimaryColor = $invoicePrimaryColor;

        return $this;
    }

    public function getInvoiceTextColor(): ?string
    {
        return $this->invoiceTextColor;
    }

    public function setInvoiceTextColor(?string $invoiceTextColor): static
    {
        $this->invoiceTextColor = $invoiceTextColor;

        return $this;
    }
    
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get the value of logoFile
     */
    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    /**
     * Set the value of logoFile
     */
    public function setLogoFile(?File $logoFile): void
    {
        $this->logoFile = $logoFile;
        // if ($logoFile) {
        //     $this->updatedAt = new \DateTime('now');
        // }
    }

    public function getThemeSelection(): ?ThemeSelection
    {
        return $this->themeSelection;
    }

    public function setThemeSelection(?ThemeSelection $themeSelection): static
    {
        $this->themeSelection = $themeSelection;

        return $this;
    }

    public function getLegalForm(): ?LegalForm
    {
        return $this->legalForm;
    }

    public function setLegalForm(?LegalForm $legalForm): static
    {
        $this->legalForm = $legalForm;

        return $this;
    }

    public function getShareCapital(): ?string
    {
        return $this->shareCapital;
    }

    public function setShareCapital(?string $shareCapital): static
    {
        $this->shareCapital = $shareCapital;

        return $this;
    }

    public function getRcs(): ?string
    {
        return $this->rcs;
    }

    public function setRcs(?string $rcs): static
    {
        $this->rcs = $rcs;

        return $this;
    }
}