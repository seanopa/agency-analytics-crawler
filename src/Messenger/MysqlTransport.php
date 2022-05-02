<?php
namespace App\Messenger;

use App\Repository\QueueRepository;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Uid\Uuid;

class MysqlTransport implements TransportInterface
{
    private QueueRepository $repository;
    private SerializerInterface $serializer;


    public function __construct(QueueRepository $repository, SerializerInterface $serializer = null)
    {
        $this->repository = $repository;
        $this->serializer = $serializer ?? new PhpSerializer();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function get(): iterable
    {
        $item = $this->repository->get();

        if (empty($item)) return [];

        $envelope = $this->serializer->decode([
            'body' => $item->getEnvelope(),
        ]);

        return [$envelope->with(new TransportMessageIdStamp((string)$item?->getId()))];
    }

    public function ack(Envelope $envelope): void
    {
        $stamp = $envelope->last(TransportMessageIdStamp::class);
        if (!$stamp instanceof TransportMessageIdStamp) {
            throw new \LogicException('No TransportMessageIdStamp found on the Envelope.');
        }

        $item = $this->repository->find($stamp->getId());

        // Mark the message as "handled"
        $this->repository->ack($item);
    }

    public function reject(Envelope $envelope): void
    {
        $stamp = $envelope->last(TransportMessageIdStamp::class);
        if (!$stamp instanceof TransportMessageIdStamp) {
            throw new \LogicException('No TransportMessageIdStamp found on the Envelope.');
        }

        $item = $this->repository->find($stamp->getId());

        $this->repository->reject($item);
    }

    public function send(Envelope $envelope): Envelope
    {
        $encodedMessage = $this->serializer->encode($envelope);
        $uuid = (string) Uuid::v4();
        // Add a message to the "my_queue" table
        $item = $this->repository->send($encodedMessage);

        return $envelope->with(new TransportMessageIdStamp((string)$item->getId()));
    }
}