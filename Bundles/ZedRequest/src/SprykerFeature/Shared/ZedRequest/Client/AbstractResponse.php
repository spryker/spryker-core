<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerEngine\Shared\Transfer\TransferInterface;

abstract class AbstractResponse extends AbstractObject implements EmbeddedTransferInterface, ResponseInterface
{

    /**
     * @var array
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
     * @param array $values
     */
    public function __construct(array $values = null)
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
            $this->values[ResponseInterface::INFO_MESSAGES][$key] = new Message($message);
        }

        foreach ($this->values[ResponseInterface::ERROR_MESSAGES] as $key => $message) {
            $this->values[ResponseInterface::ERROR_MESSAGES][$key] = new Message($message);
        }

        foreach ($this->values[ResponseInterface::SUCCESS_MESSAGES] as $key => $message) {
            $this->values[ResponseInterface::SUCCESS_MESSAGES][$key] = new Message($message);
        }
    }

    /**
     * @return Message[]
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
            if ($errorMessage->getMessage() === $messageString) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $errorMessages
     *
     * @return self
     */
    public function addErrorMessages(array $errorMessages)
    {
        foreach ($errorMessages as $errorMessage) {
            $this->addErrorMessage($errorMessage);
        }

        return $this;
    }

    /**
     * @param Message $errorMessage
     *
     * @return self
     */
    public function addErrorMessage(Message $errorMessage)
    {
        $this->values[ResponseInterface::ERROR_MESSAGES][] = $errorMessage;

        return $this;
    }

    /**
     * @return Message[]
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
            if ($message->getMessage() === $messageString) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Message $message
     *
     * @return self
     */
    public function addInfoMessage(Message $message)
    {
        $this->values[ResponseInterface::INFO_MESSAGES][] = $message;

        return $this;
    }

    /**
     * @param array $messages
     *
     * @return self
     */
    public function addInfoMessages(array $messages)
    {
        foreach ($messages as $message) {
            $this->addInfoMessage($message);
        }

        return $this;
    }

    /**
     * @return Message[]
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
        foreach ($successMessages as $sucessMessage) {
            if ($sucessMessage->getMessage() === $messageString) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $successMessages
     *
     * @return self
     */
    public function addSuccessMessages(array $successMessages)
    {
        foreach ($successMessages as $successMessage) {
            $this->addSuccessMessage($successMessage);
        }

        return $this;
    }

    /**
     * @param Message $successMessage
     *
     * @return self
     */
    public function addSuccessMessage(Message $successMessage)
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
     * @return self
     */
    public function setSuccess($success)
    {
        $this->values[ResponseInterface::SUCCESS] = $success;

        return $this;
    }

    /**
     * @return TransferInterface
     */
    public function getTransfer()
    {
        if (!empty($this->values[ResponseInterface::TRANSFER_CLASSNAME]) &&
            !empty($this->values[ResponseInterface::TRANSFER])) {
            $transfer = $this->createTransferObject(
                $this->values[ResponseInterface::TRANSFER_CLASSNAME]
            );
            $transfer->fromArray($this->values[ResponseInterface::TRANSFER], true);

            return $transfer;
        }

        return;
    }

    /**
     * @param $transferClassName
     *
     * @return TransferInterface
     */
    private function createTransferObject($transferClassName)
    {
        $transfer = new $transferClassName();

        return $transfer;
    }

    /**
     * @param TransferInterface $transferObject
     *
     * @return self
     */
    public function setTransfer(TransferInterface $transferObject)
    {
        $this->values[ResponseInterface::TRANSFER] = $transferObject->toArray();
        $this->values[ResponseInterface::TRANSFER_CLASSNAME] = get_class($transferObject);

        return $this;
    }

}
