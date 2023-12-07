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

class MessagePublishLogger implements MessagePublishLoggerInterface
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     * @param float $startMicrotime
     *
     * @return void
     */
    public function logInfo(
        TransferInterface $messageTransfer,
        float $startMicrotime
    ): void {
        $logContext = $this->getContext($messageTransfer, $startMicrotime);

        $this->getLogger()->log(Logger::INFO, '', [
            static::KEY_CODE => $this->messageBrokerConfig->getMessageBrokerCallSuccessCode(),
            static::KEY_CODE_NAME => $this->messageBrokerConfig->getMessageBrokerCallSuccessCodeName(),
        ] + $logContext);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     * @param float $startMicrotime
     * @param string $message
     *
     * @return void
     */
    public function logError(
        TransferInterface $messageTransfer,
        float $startMicrotime,
        string $message
    ): void {
        $logContext = $this->getContext($messageTransfer, $startMicrotime);

        $this->getLogger()->log(Logger::ERROR, $message, [
            static::KEY_CODE => $this->messageBrokerConfig->getMessageBrokerCallErrorCode(),
            static::KEY_CODE_NAME => $this->messageBrokerConfig->getMessageBrokerCallErrorCodeName(),
        ] + $logContext);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     * @param float $startMicrotime
     *
     * @return array<string, mixed>
     */
    protected function getContext(
        TransferInterface $messageTransfer,
        float $startMicrotime
    ): array {
        return [
            'duration' => $this->getDuration($startMicrotime),
            'activityType' => 'Message publishing',
        ] + $this->getLoggerMessageAttributes($messageTransfer);
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
     * @return int
     */
    protected function getDuration(float $startMicrotime): int
    {
        return (int)ceil((microtime(true) - $startMicrotime) * 1000);
    }
}
