<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Logger;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Monolog\Logger;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class MessageLogger implements MessageLoggerInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const KEY_CODE = 'code';

    /**
     * @var string
     */
    protected const KEY_CODE_NAME = 'codeName';

    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $messageBrokerConfig;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $messageBrokerConfig
     */
    public function __construct(MessageBrokerConfig $messageBrokerConfig)
    {
        $this->messageBrokerConfig = $messageBrokerConfig;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param float $startMicrotime
     *
     * @return void
     */
    public function logInfo(Envelope $envelope, float $startMicrotime): void
    {
        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer */
        $messageTransfer = $envelope->getMessage();
        $isEnvelopeReceived = $this->isEnvelopeReceived($envelope);
        $logContext = $this->getContext($messageTransfer, $startMicrotime, $isEnvelopeReceived);

        $this->getLogger()->log(Logger::INFO, '', [
            static::KEY_CODE => $isEnvelopeReceived
                ? $this->messageBrokerConfig->getMessageBrokerConsumeSuccessCode()
                : $this->messageBrokerConfig->getMessageBrokerCallSuccessCode(),
            static::KEY_CODE_NAME => $isEnvelopeReceived
                ? $this->messageBrokerConfig->getMessageBrokerConsumeSuccessCodeName()
                : $this->messageBrokerConfig->getMessageBrokerCallSuccessCodeName(),
        ] + $logContext);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param float $startMicrotime
     * @param string $errorMessage
     *
     * @return void
     */
    public function logError(Envelope $envelope, float $startMicrotime, string $errorMessage): void
    {
        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer */
        $messageTransfer = $envelope->getMessage();
        $isEnvelopeReceived = $this->isEnvelopeReceived($envelope);
        $logContext = $this->getContext($messageTransfer, $startMicrotime, $isEnvelopeReceived);

        $this->getLogger()->log(Logger::ERROR, $errorMessage, [
            static::KEY_CODE => $isEnvelopeReceived
                ? $this->messageBrokerConfig->getMessageBrokerConsumeErrorCode()
                : $this->messageBrokerConfig->getMessageBrokerCallErrorCode(),
            static::KEY_CODE_NAME => $isEnvelopeReceived
                ? $this->messageBrokerConfig->getMessageBrokerConsumeErrorCodeName()
                : $this->messageBrokerConfig->getMessageBrokerCallErrorCodeName(),
        ] + $logContext);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return bool
     */
    protected function isEnvelopeReceived(Envelope $envelope): bool
    {
        return $envelope->last(ReceivedStamp::class) !== null;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     * @param float $startMicrotime
     * @param bool $isEnvelopeReceived
     *
     * @return array<string, mixed>
     */
    protected function getContext(
        TransferInterface $messageTransfer,
        float $startMicrotime,
        bool $isEnvelopeReceived
    ): array {
        $context = [
            'duration' => sprintf('%ss', $this->getDurationInSeconds($startMicrotime)),
            'activityType' => $isEnvelopeReceived ? 'Message consuming' : 'Message publishing',
        ] + $this->getLoggerMessageAttributes($messageTransfer);

        if ($this->messageBrokerConfig->isMessageBodyIncludedInLogs()) {
            $messageBody = $messageTransfer->toArray();
            unset($messageBody['message_attributes']);
            $context['messageBody'] = $messageBody;
        }

        return $context;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return array<string, string>
     */
    protected function getLoggerMessageAttributes(TransferInterface $messageTransfer): array
    {
        $loggerMessageAttributes = [];
        if (method_exists($messageTransfer, 'getMessageAttributes') && $messageTransfer->getMessageAttributes()) {
            $loggerMessageAttributes = array_diff_key(
                $messageTransfer->getMessageAttributes()->toArrayRecursiveCamelCased(),
                array_flip($this->messageBrokerConfig->getProtectedMessageAttributes()),
            );
        }

        //It is not allowed to log authorization token for security reasons
        if (isset($loggerMessageAttributes[MessageAttributesTransfer::AUTHORIZATION])) {
            unset($loggerMessageAttributes[MessageAttributesTransfer::AUTHORIZATION]);
        }

        return $loggerMessageAttributes;
    }

    /**
     * @param float $startMicrotime
     *
     * @return float
     */
    protected function getDurationInSeconds(float $startMicrotime): float
    {
        return round(microtime(true) - $startMicrotime, 2);
    }
}
