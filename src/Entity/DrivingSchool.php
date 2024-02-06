<?php

namespace App\Entity;

use App\Repository\DrivingSchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: DrivingSchoolRepository::class)]
#[Vich\Uploadable]
class DrivingSchool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\OneToMany(mappedBy: 'drivingSchool', targetEntity: Client::class)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'drivingSchool', targetEntity: Contract::class)]
    private Collection $contracts;

    #[Vich\UploadableField(mapping: 'drivingSchoolLogo', fileNameProperty: 'logoName')]
    #[Assert\Image(
        maxSize: '500k',
        mimeTypes: ['image/jpeg', 'image/png'],
        maxHeight: 500,
        maxSizeMessage: 'Le logo ne doit pas dépasser 500ko.',
        mimeTypesMessage: 'Le logo doit être au format JPG ou PNG.',
        maxHeightMessage: 'Le logo ne doit pas dépasser 500px de hauteur.'
    )]
    private ?File $logoFile = null;

    #[ORM\OneToMany(mappedBy: 'drivingSchool', targetEntity: Invoice::class)]
    private Collection $invoices;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'drivingSchools')]
    private Collection $users;

    #[ORM\Column(nullable: true)]
    private ?string $logoName = null;

    #[ORM\Column(nullable: true)]
    private ?int $logoSize = null;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->contracts = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setDrivingSchool($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getDrivingSchool() === $this) {
                $client->setDrivingSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): static
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setDrivingSchool($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): static
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getDrivingSchool() === $this) {
                $contract->setDrivingSchool(null);
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
            $invoice->setDrivingSchool($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getDrivingSchool() === $this) {
                $invoice->setDrivingSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addDrivingSchool($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeDrivingSchool($this);
        }

        return $this;
    }

    public function setLogoFile(?File $imageFile = null): void
    {
        $this->logoFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoName(?string $imageName): void
    {
        $this->logoName = $imageName;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function getLogoSize(): ?int
    {
        return $this->logoSize;
    }

    public function setLogoSize(?int $logoSize): static
    {
        $this->logoSize = $logoSize;

        return $this;
    }
}
