<?php

namespace App\Messenger;

use App\Message\MessageInterface;
use Symfony\Component\Messenger\Envelope;
use \Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

/**
 * Class MsqlJsonSerializer
 * @package App\Messenger
 */
class MsqlJsonSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'] ?? [];
        $data = json_decode($body, true);

        $message = $data['class']::getInstanceFromArray($data['content']);

        // in case of redelivery, unserialize any stamps
        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }
        return new Envelope($message, $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        if ($message instanceof MessageInterface) {
            $data = [
                'class' => get_class($message),
                'content' => json_decode(json_encode($message), true)
            ];
        } else {
            throw new \Exception('Unsupported message class');
        }

        return [
            'body' => json_encode($data),
            'headers' => [],
        ];
    }
}