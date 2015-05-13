<?php

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\TransferLocatorHelper;
use SprykerEngine\Shared\Transfer\TransferInterface;

abstract class AbstractResponse extends AbstractObject implements EmbeddedTransferInterface, ResponseInterface
{
    /**
     * @var array
     */
    protected $values = [
        'messages' => [],
        'errorMessages' => [],
        'success' => true,
        'transfer' => null,
        'transferClassName' => null
    ];

    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     * @param array $values
     */
    public function __construct(LocatorLocatorInterface $locator, array $values = null)
    {
        $this->locator = $locator;
        parent::__construct($values);
    }

    /**
     * @param array $values
     */
    public function fromArray(array $values)
    {
        parent::fromArray($values);

        foreach ($this->values['messages'] as $key => $message) {
            $this->values['messages'][$key] = new Message($message);
        }

        foreach ($this->values['errorMessages'] as $key => $message) {
            $this->values['errorMessages'][$key] = new Message($message);
        }
    }

    /**
     * @return Message[]
     */
    public function getErrorMessages()
    {
        return $this->values['errorMessages'];
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
     * @param Message $errorMessage
     *
     * @return $this
     */
    public function addErrorMessage(Message $errorMessage)
    {
        $this->values['errorMessages'][] = $errorMessage;

        return $this;
    }

    /**
     * @return Message[]
     */
    public function getMessages()
    {
        return $this->values['messages'];
    }

    /**
     * @param string $messageString
     *
     * @return bool
     */
    public function hasMessage($messageString)
    {
        $messages = $this->getMessages();
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
     * @return $this
     */
    public function addMessage(Message $message)
    {
        $this->values['messages'][] = $message;

        return $this;
    }

    /**
     * @param array $messages
     *
     * @return $this
     */
    public function addMessages(array $messages)
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->values['success'];
    }

    /**
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->values['success'] = $success;

        return $this;
    }

    /**
     * @return TransferInterface
     */
    public function getTransfer()
    {
        if (!empty($this->values['transferClassName']) && !empty($this->values['transfer'])) {
            $transfer = $this->createTransferObject(
                $this->values['transferClassName']
            );
            $transfer->fromArray($this->values['transfer']);

            return $transfer;
        }
        return null;
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
     * @return $this
     */
    public function setTransfer(TransferInterface $transferObject)
    {
        $this->values['transfer'] = $transferObject->toArray(false);
        $this->values['transferClassName'] = get_class($transferObject);

        return $this;
    }
}
