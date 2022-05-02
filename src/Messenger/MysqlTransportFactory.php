<?php
namespace App\Messenger;

use App\Repository\QueueRepository;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class MysqlTransportFactory implements TransportFactoryInterface
{
    private QueueRepository $repository;

    public function __construct(QueueRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createTransport(string $dsn, array $options, MsqlJsonSerializer|SerializerInterface $serializer): TransportInterface
    {
        return new MysqlTransport($this->repository, $serializer);
    }

    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'mysql://');
    }
}