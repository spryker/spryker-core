<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\ZedRequest\Client\Exception\TransferNotFoundException;

abstract class AbstractResponse extends AbstractObject implements EmbeddedTransferInterface, ResponseInterface
{
    /**
     * @var array<string, mixed>
     */
    protected $values = [
        ResponseInterface::INFO_MESSAGES => [],
        ResponseInterface::ERROR_MESSAGES => [],
        ResponseInterface::SUCCESS_MESSAGES => [],
        ResponseInterface::SUCCESS => true,
        ResponseInterface::TRANSFER => null,
        ResponseInterface::TRANSFER_CLASSNAME => null,
    ];

    /**
     * @param array|null $values
     */
    public function __construct(?array $values = null)
    {
        parent::__construct($values);
    }

    /**
     * @param array $values
     *
     * @return void
     */
    public function fromArray(array $values)
    {
        parent::fromArray($values);

        foreach ($this->values[ResponseInterface::INFO_MESSAGES] as $key => $message) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue($message['value']);
            $messageTransfer->setParameters($message['parameters']);

            $this->values[ResponseInterface::INFO_MESSAGES][$key] = $messageTransfer;
        }

        foreach ($this->values[ResponseInterface::ERROR_MESSAGES] as $key => $message) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue($message['value']);
            $messageTransfer->setParameters($message['parameters']);

            $this->values[ResponseInterface::ERROR_MESSAGES][$key] = $messageTransfer;
        }

        foreach ($this->values[ResponseInterface::SUCCESS_MESSAGES] as $key => $message) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue($message['value']);
            $messageTransfer->setParameters($message['parameters']);

            $this->values[ResponseInterface::SUCCESS_MESSAGES][$key] = $messageTransfer;
        }
    }

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getErrorMessages()
    {
        return $this->values[ResponseInterface::ERROR_MESSAGES];
    }

    /**
     * @param string $messageString
     *
     * @return bool
     */
    public function hasErrorMessage($messageString)
    {
        $errorMessages = $this->getErrorMessages();
        foreach ($errorMessages as $errorMessage) {
            if ($errorMessage->getValue() === $messageString) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $errorMessages
     *
     * @return $this
     */
    public function addErrorMessages(array $errorMessages)
    {
        foreach ($errorMessages as $errorMessage) {
            $this->addErrorMessage($errorMessage);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $errorMessage
     *
     * @return $this
     */
    public function addErrorMessage(MessageTransfer $errorMessage)
    {
        $this->values[ResponseInterface::ERROR_MESSAGES][] = $errorMessage;

        return $this;
    }

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getInfoMessages()
    {
        return $this->values[ResponseInterface::INFO_MESSAGES];
    }

    /**
     * @param string $messageString
     *
     * @return bool
     */
    public function hasInfoMessage($messageString)
    {
        $messages = $this->getInfoMessages();
        foreach ($messages as $message) {
            if ($message->getValue() === $messageString) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return $this
     */
    public function addInfoMessage(MessageTransfer $message)
    {
        $this->values[ResponseInterface::INFO_MESSAGES][] = $message;

        return $this;
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $messages
     *
     * @return $this
     */
    public function addInfoMessages(array $messages)
    {
        foreach ($messages as $message) {
            $this->addInfoMessage($message);
        }

        return $this;
    }

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getSuccessMessages()
    {
        return $this->values[ResponseInterface::SUCCESS_MESSAGES];
    }

    /**
     * @param string $messageString
     *
     * @return bool
     */
    public function hasSuccessMessage($messageString)
    {
        $successMessages = $this->getSuccessMessages();
        foreach ($successMessages as $successMessage) {
            if ($successMessage->getValue() === $messageString) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $successMessages
     *
     * @return $this
     */
    public function addSuccessMessages(array $successMessages)
    {
        foreach ($successMessages as $successMessage) {
            $this->addSuccessMessage($successMessage);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $successMessage
     *
     * @return $this
     */
    public function addSuccessMessage(MessageTransfer $successMessage)
    {
        $this->values[ResponseInterface::SUCCESS_MESSAGES][] = $successMessage;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->values[ResponseInterface::SUCCESS];
    }

    /**
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->values[ResponseInterface::SUCCESS] = $success;

        return $this;
    }

    /**
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\TransferNotFoundException
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getTransfer()
    {
        if (!empty($this->values[ResponseInterface::TRANSFER_CLASSNAME])) {
            $transfer = $this->createTransferObject(
                $this->values[ResponseInterface::TRANSFER_CLASSNAME],
            );
            $transfer->fromArray($this->values[ResponseInterface::TRANSFER], true);

            return $transfer;
        }

        throw new TransferNotFoundException('No class name given for Transfer generation.');
    }

    /**
     * @param string $transferClassName
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    private function createTransferObject($transferClassName)
    {
        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer */
        $transfer = new $transferClassName();

        return $transfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transferObject)
    {
        $this->values[ResponseInterface::TRANSFER] = $transferObject->modifiedToArray();
        $this->values[ResponseInterface::TRANSFER_CLASSNAME] = get_class($transferObject);

        return $this;
    }
}
