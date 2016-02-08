<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\ZedRequest\Client;

use Spryker\Shared\Transfer\TransferInterface;

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
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
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
     * @param \Spryker\Shared\ZedRequest\Client\Message $errorMessage
     *
     * @return $this
     */
    public function addErrorMessage(Message $errorMessage)
    {
        $this->values[ResponseInterface::ERROR_MESSAGES][] = $errorMessage;

        return $this;
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
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
     * @param \Spryker\Shared\ZedRequest\Client\Message $message
     *
     * @return $this
     */
    public function addInfoMessage(Message $message)
    {
        $this->values[ResponseInterface::INFO_MESSAGES][] = $message;

        return $this;
    }

    /**
     * @param array $messages
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
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
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
            if ($successMessage->getMessage() === $messageString) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $successMessages
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
     * @param \Spryker\Shared\ZedRequest\Client\Message $successMessage
     *
     * @return $this
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
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->values[ResponseInterface::SUCCESS] = $success;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Transfer\TransferInterface|null
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

        return null;
    }

    /**
     * @param $transferClassName
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    private function createTransferObject($transferClassName)
    {
        $transfer = new $transferClassName();

        return $transfer;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
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
