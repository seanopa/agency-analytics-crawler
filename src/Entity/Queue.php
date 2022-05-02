<?php

namespace App\Entity;

use App\Repository\QueueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Queue
 *
 * @ORM\Table(name="aa_queue")
 * @ORM\Entity(repositoryClass=QueueRepository::class)
 */
class Queue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=5000, nullable=false)
     */
    protected $envelope;

    /**
     * @var string|null
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"="0"})
     */
    protected $handled = 0;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $delivered_at;

    public function getId()
    {
        return $this->id;
    }

    public function getEnvelope(): ?string
    {
        return $this->envelope;
    }

    public function setEnvelope(string $envelope): self
    {
        $this->envelope = $envelope;

        return $this;
    }

    public function getHandled(): ?bool
    {
        return $this->handled;
    }

    public function setHandled(?bool $handled): self
    {
        $this->handled = $handled;

        return $this;
    }

    public function getDeliveredAt(): ?\DateTimeInterface
    {
        return $this->delivered_at;
    }

    public function setDeliveredAt(?\DateTimeInterface $delivered_at): self
    {
        $this->delivered_at = $delivered_at;

        return $this;
    }
}