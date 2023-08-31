<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Serializer;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageMetadataTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\MessageBrokerAws\Business\Exception\EnvelopDecodingFailedException;
use Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

class TransferSerializer implements SerializerInterface
{
    /**
     * @var string
     */
    protected const HEADER_KEY_EMITTER = 'emitter';

    /**
     * @var string
     */
    protected const HEADER_KEY_PUBLISHER = 'publisher';

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected SymfonySerializerInterface $serializer;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface
     */
    protected MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var string
     */
    protected string $format = 'json';

    /**
     * @var array<bool>
     */
    protected array $context = [];

    /**
     * @var array<\Spryker\Zed\MessageBrokerAws\Business\MessageDataFilter\MessageDataFilterInterface>
     */
    protected array $messageDataFilters;

    /**
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService
     * @param array<\Spryker\Zed\MessageBrokerAws\Business\MessageDataFilter\MessageDataFilterInterface> $messageDataFilters
     */
    public function __construct(
        SymfonySerializerInterface $serializer,
        MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService,
        array $messageDataFilters = []
    ) {
        $this->serializer = $serializer;
        $this->utilEncodingService = $utilEncodingService;
        $this->messageDataFilters = $messageDataFilters;
    }

    /**
     * @param array<string, mixed> $encodedEnvelope
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\EnvelopDecodingFailedException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        if (empty($encodedEnvelope['body'])) {
            throw new EnvelopDecodingFailedException('Encoded envelope should have a "body".');
        }

        if (empty($encodedEnvelope['headers'])) {
            throw new EnvelopDecodingFailedException('Encoded envelope should have some "headers".');
        }

        if (isset($encodedEnvelope['messageId']) && !$encodedEnvelope['messageId']) {
            throw new EnvelopDecodingFailedException('Encoded envelope should have a "messageId".');
        }

        $messageAttributesTransfer = $this->mapEncodedEnvelopeToMessageAttributesTransfer($encodedEnvelope);
        $messageTransferClassName = $this->getMessageTransferClassName($encodedEnvelope, $messageAttributesTransfer);

        try {
            /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer */
            $messageTransfer = $this->serializer->deserialize($encodedEnvelope['body'], $messageTransferClassName, $this->format, $this->context);
        } catch (ExceptionInterface $e) {
            throw new EnvelopDecodingFailedException('Could not decode message: ' . $e->getMessage(), $e->getCode(), $e);
        }

        if (!method_exists($messageTransfer, 'setMessageAttributes')) {
            throw new EnvelopDecodingFailedException(sprintf(
                'Could not decode message: "%s" contains no "setMessageAttributes" method.',
                get_class($messageTransfer),
            ));
        }

        $messageTransfer->setMessageAttributes($messageAttributesTransfer);

        return new Envelope($messageTransfer);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\EnvelopDecodingFailedException
     *
     * @return array<string, mixed>
     */
    public function encode(Envelope $envelope): array
    {
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);

        $messageTransfer = $envelope->getMessage();

        if (!($messageTransfer instanceof AbstractTransfer)) {
            throw new EnvelopDecodingFailedException(sprintf('Could not decode message, expected type of "%s" but got "%s".', AbstractTransfer::class, gettype($messageTransfer)));
        }

        if (!method_exists($messageTransfer, 'getMessageAttributes')) {
            throw new EnvelopDecodingFailedException(sprintf('Could not decode message, expected to have a method "getMessageAttributes()" but it was not found in "%s".', get_class($messageTransfer)));
        }

        /** @var \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer */
        $messageAttributesTransfer = $messageTransfer->getMessageAttributes();

        if (!($messageAttributesTransfer instanceof MessageAttributesTransfer)) {
            throw new EnvelopDecodingFailedException(sprintf('Could not decode message, expected to have a Transfer object "%s" inside your "%s" message transfer but it is empty.', MessageAttributesTransfer::class, get_class($messageTransfer)));
        }
        $messageAttributesTransfer->getTransferNameOrFail();

        $messageData = $this->extractMessageData($messageTransfer);

        $headers = $messageAttributesTransfer->modifiedToArray(true, true);

        if (isset($headers[static::HEADER_KEY_EMITTER])) {
            $headers[static::HEADER_KEY_PUBLISHER] = $headers[static::HEADER_KEY_EMITTER];
            unset($headers[static::HEADER_KEY_EMITTER]);
        }

        $headers += ['Content-Type' => 'application/json'];

        return [
            'body' => $this->serializer->serialize($messageData, $this->format, $this->context),
            'bodyRaw' => $messageData,
            'headers' => $headers,
        ];
    }

    /**
     * @param array<string, mixed> $encodedEnvelope
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    protected function mapEncodedEnvelopeToMessageAttributesTransfer(
        array $encodedEnvelope
    ): MessageAttributesTransfer {
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->fromArray($encodedEnvelope['headers'], true)
            ->setPublisher(null)
            ->setEmitter($encodedEnvelope['headers'][static::HEADER_KEY_PUBLISHER] ?? null);

        if (isset($encodedEnvelope['messageId'])) {
            $messageAttributesTransfer->setMetadata(
                (new MessageMetadataTransfer())->setMessageId($encodedEnvelope['messageId']),
            );
        }

        // It is needed for BC in scenarios where StoreReference is empty but still utilized in certain contexts.
        if (empty($messageAttributesTransfer->getStoreReference())) {
            $messageAttributesTransfer->setStoreReference($messageAttributesTransfer->getTenantIdentifier());
        }

        return $messageAttributesTransfer;
    }

    /**
     * @param array<string, mixed> $encodedEnvelope
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\EnvelopDecodingFailedException
     * @throws \Symfony\Component\Messenger\Exception\MessageDecodingFailedException
     *
     * @return string
     */
    protected function getMessageTransferClassName(
        array $encodedEnvelope,
        MessageAttributesTransfer $messageAttributesTransfer
    ): string {
        if (empty($encodedEnvelope['headers']['transferName']) && empty($encodedEnvelope['headers']['name'])) {
            throw new EnvelopDecodingFailedException('Encoded envelope does not have a "transferName" or "name" header. The "transferName" or "name" is referring to a Transfer class that is used to unserialize the message data.');
        }

        $messageTransferClassName = '';
        if ($messageAttributesTransfer->getTransferName()) {
            $messageTransferClassName = sprintf('\\Generated\\Shared\\Transfer\\%sTransfer', $messageAttributesTransfer->getTransferName());
        }
        if (!$messageTransferClassName) {
            $messageTransferClassName = sprintf('\\Generated\\Shared\\Transfer\\%sTransfer', $messageAttributesTransfer->getName());
        }

        if (!class_exists($messageTransferClassName)) {
            throw new MessageDecodingFailedException(sprintf('Could not find the "%s" transfer object to unserialize the data.', $messageTransferClassName));
        }

        return $messageTransferClassName;
    }

    /**
     * Extract and filter fields from message transfer
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer
     *
     * @return array<string, mixed>
     */
    protected function extractMessageData(AbstractTransfer $messageTransfer): array
    {
        $messageData = $messageTransfer->modifiedToArray(true, true);
        unset(
            $messageData['messageAttributes'],
            $messageData['dataFilterConfiguration'],
        );

        foreach ($this->messageDataFilters as $messageDataFilter) {
            $messageData = $messageDataFilter->filter($messageData, $messageTransfer);
        }

        return $messageData;
    }
}
