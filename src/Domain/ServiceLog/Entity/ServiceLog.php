<?php

namespace App\Domain\ServiceLog\Entity;

use App\Domain\ServiceLog\Repository\ServiceLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceLogRepository::class)]
#[ORM\Table(name: 'service_log')]
#[ORM\Index(name: 'idx_service', columns: ['service'])]
#[ORM\Index(name: 'idx_requested_at', columns: ['requested_at'])]
#[ORM\Index(name: 'idx_status_code', columns: ['status_code'])]
class ServiceLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $service = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $requested_at = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $status_code = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $request = null;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): static
    {
        $this->service = $service;
        return $this;
    }

    public function getRequestedAt(): ?\DateTimeInterface
    {
        return $this->requested_at;
    }

    public function setRequestedAt(\DateTimeInterface $requested_at): static
    {
        $this->requested_at = $requested_at;
        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->status_code;
    }

    public function setStatusCode(int $status_code): static
    {
        $this->status_code = $status_code;
        return $this;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function setRequest(string $request): static
    {
        $this->request = $request;
        return $this;
    }
}
