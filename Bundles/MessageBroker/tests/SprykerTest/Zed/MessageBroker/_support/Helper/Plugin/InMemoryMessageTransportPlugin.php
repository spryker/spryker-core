<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginAcceptClientInterface;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Receiver\QueueReceiverInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class InMemoryMessageTransportPlugin extends AbstractPlugin implements MessageSenderPluginInterface, MessageReceiverPluginInterface, TransportInterface, QueueReceiverInterface, MessageSenderPluginAcceptClientInterface
{
    /**
     * @var \Symfony\Component\Messenger\Transport\InMemoryTransport
     */
    protected InMemoryTransport $transport;

    /**
     * @param \Symfony\Component\Messenger\Transport\InMemoryTransport $transport
     */
    public function __construct(InMemoryTransport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param string $client
     *
     * @return string
     */
    public function acceptClient(string $client): string
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTransportName(): string
    {
        return 'in-memory';
    }

    /**
     * @return iterable
     */
    public function get(): iterable
    {
        return $this->transport->get();
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string> $queueNames
     *
     * @return iterable<\Symfony\Component\Messenger\Envelope>
     */
    public function getFromQueues(array $queueNames): iterable
    {
        return $this->get();
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function ack(Envelope $envelope): void
    {
        $this->transport->ack($envelope);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function reject(Envelope $envelope): void
    {
        $this->transport->reject($envelope);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        return $this->transport->send($envelope);
    }

    /**
     * @return \Symfony\Component\Messenger\Transport\InMemoryTransport
     */
    public function getTransport(): InMemoryTransport
    {
        return $this->transport;
    }
}
