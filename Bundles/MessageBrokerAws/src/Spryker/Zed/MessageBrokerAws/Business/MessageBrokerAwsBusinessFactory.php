<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business;

use Aws\Sns\SnsClient;
use Aws\Sqs\SqsClient;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Config\JsonToArrayConfigFormatter;
use Spryker\Zed\MessageBrokerAws\Business\Queue\AwsSqsQueuesCreator;
use Spryker\Zed\MessageBrokerAws\Business\Queue\AwsSqsQueuesCreatorInterface;
use Spryker\Zed\MessageBrokerAws\Business\Queue\AwsSqsQueuesSubscriber;
use Spryker\Zed\MessageBrokerAws\Business\Queue\AwsSqsQueuesSubscriberInterface;
use Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\Locator\ReceiverClientLocator;
use Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\Locator\ReceiverClientLocatorInterface;
use Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface;
use Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\SqsReceiverClient;
use Spryker\Zed\MessageBrokerAws\Business\Receiver\Receiver;
use Spryker\Zed\MessageBrokerAws\Business\Receiver\ReceiverInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatter;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\HttpSenderClient;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Locator\SenderClientLocator;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Locator\SenderClientLocatorInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SnsSenderClient;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SqsSenderClient;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Sender;
use Spryker\Zed\MessageBrokerAws\Business\Sender\SenderInterface;
use Spryker\Zed\MessageBrokerAws\Business\Serializer\TransferSerializer;
use Spryker\Zed\MessageBrokerAws\Business\Sns\AwsSnsTopicCreator;
use Spryker\Zed\MessageBrokerAws\Business\Sns\AwsSnsTopicCreatorInterface;
use Spryker\Zed\MessageBrokerAws\Dependency\Facade\MessageBrokerAwsToStoreFacadeInterface;
use Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsDependencyProvider;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

/**
 * @method \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig getConfig()
 */
class MessageBrokerAwsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sender\SenderInterface
     */
    public function createSender(): SenderInterface
    {
        return new Sender(
            $this->getConfig(),
            $this->createSenderClientLocator(),
            $this->createConfigFormatter(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Locator\SenderClientLocatorInterface
     */
    public function createSenderClientLocator(): SenderClientLocatorInterface
    {
        return new SenderClientLocator(
            $this->getConfig(),
            $this->getSenderClients(),
            $this->createConfigFormatter(),
        );
    }

    /**
     * @return array<string, \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface>
     */
    public function getSenderClients(): array
    {
        return [
            'sns' => $this->createSnsSenderClient(),
            'sqs' => $this->createSqsSenderClient(),
            'http' => $this->createHttpSenderClient(),
        ];
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface
     */
    public function createSnsSenderClient(): SenderClientInterface
    {
        return new SnsSenderClient(
            $this->getConfig(),
            $this->createSerializer(),
            $this->createConfigFormatter(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface
     */
    public function createSqsSenderClient(): SenderClientInterface
    {
        return new SqsSenderClient(
            $this->getConfig(),
            $this->createSerializer(),
            $this->createConfigFormatter(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface
     */
    public function createHttpSenderClient(): SenderClientInterface
    {
        return new HttpSenderClient(
            $this->getConfig(),
            $this->createSerializer(),
            $this->createConfigFormatter(),
            $this->createHttpHeaderFormatter(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Receiver\ReceiverInterface
     */
    public function createReceiver(): ReceiverInterface
    {
        return new Receiver(
            $this->getConfig(),
            $this->createReceiverClientLocator(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\Locator\ReceiverClientLocatorInterface
     */
    public function createReceiverClientLocator(): ReceiverClientLocatorInterface
    {
        return new ReceiverClientLocator(
            $this->getConfig(),
            $this->getReceiverClients(),
            $this->createConfigFormatter(),
        );
    }

    /**
     * @return array<string, \Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface>
     */
    public function getReceiverClients(): array
    {
        return [
            'sqs' => $this->createSqsReceiverClient(),
        ];
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface
     */
    public function createSqsReceiverClient(): ReceiverClientInterface
    {
        return new SqsReceiverClient(
            $this->getConfig(),
            $this->createSerializer(),
            $this->createConfigFormatter(),
        );
    }

    /**
     * @return \Symfony\Component\Messenger\Transport\Serialization\SerializerInterface
     */
    public function createSerializer(): SerializerInterface
    {
        return new TransferSerializer(
            $this->createSymfonySerializer(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Symfony\Component\Serializer\Serializer
     */
    public function createSymfonySerializer(): SymfonySerializer
    {
        return new SymfonySerializer(
            $this->getSerializerNormalizer(),
            $this->getSerializerEncoders(),
        );
    }

    /**
     * @return array<(\Symfony\Component\Serializer\Normalizer\NormalizerInterface|\Symfony\Component\Serializer\Normalizer\DenormalizerInterface)>
     */
    public function getSerializerNormalizer(): array
    {
        return [
            $this->createArrayDenormalizer(),
            $this->createObjectNormalizer(),
        ];
    }

    /**
     * @return \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer
     */
    public function createArrayDenormalizer(): ArrayDenormalizer
    {
        return new ArrayDenormalizer();
    }

    /**
     * @return \Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    public function createObjectNormalizer(): NormalizerInterface
    {
        return new ObjectNormalizer();
    }

    /**
     * @return array<(\Symfony\Component\Serializer\Encoder\EncoderInterface|\Symfony\Component\Serializer\Encoder\DecoderInterface)>
     */
    public function getSerializerEncoders(): array
    {
        return [
            $this->createJsonEncoder(),
        ];
    }

    /**
     * @return \Symfony\Component\Serializer\Encoder\JsonEncoder
     */
    public function createJsonEncoder(): JsonEncoder
    {
        return new JsonEncoder();
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface
     */
    public function createConfigFormatter(): ConfigFormatterInterface
    {
        return new JsonToArrayConfigFormatter(
            $this->getStoreFacade(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Queue\AwsSqsQueuesCreatorInterface
     */
    public function createAwsSqsQueuesCreator(): AwsSqsQueuesCreatorInterface
    {
        return new AwsSqsQueuesCreator(
            $this->getAwsSqsClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sns\AwsSnsTopicCreatorInterface
     */
    public function createAwsSnsTopicCreator(): AwsSnsTopicCreatorInterface
    {
        return new AwsSnsTopicCreator(
            $this->getAwsSnsClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Queue\AwsSqsQueuesSubscriberInterface
     */
    public function createAwsSqsQueueSubscriber(): AwsSqsQueuesSubscriberInterface
    {
        return new AwsSqsQueuesSubscriber(
            $this->getAwsSnsClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Aws\Sqs\SqsClient
     */
    public function getAwsSqsClient(): SqsClient
    {
        return $this->getProvidedDependency(MessageBrokerAwsDependencyProvider::CLIENT_AWS_SQS);
    }

    /**
     * @return \Aws\Sns\SnsClient
     */
    public function getAwsSnsClient(): SnsClient
    {
        return $this->getProvidedDependency(MessageBrokerAwsDependencyProvider::CLIENT_AWS_SNS);
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface
     */
    public function createHttpHeaderFormatter(): HttpHeaderFormatterInterface
    {
        return new HttpHeaderFormatter();
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Dependency\Facade\MessageBrokerAwsToStoreFacadeInterface
     */
    protected function getStoreFacade(): MessageBrokerAwsToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MessageBrokerAwsDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): MessageBrokerAwsToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MessageBrokerAwsDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
