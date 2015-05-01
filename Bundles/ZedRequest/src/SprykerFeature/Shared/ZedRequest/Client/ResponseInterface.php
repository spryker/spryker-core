<?php

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerFeature\Shared\Library\TransferObject\TransferInterface;

interface ResponseInterface
{
    /**
     * @param array $values
     */
    public function fromArray(array $values);

    /**
     * @return Message[]
     */
    public function getErrorMessages();

    /**
     * @param string $messageString
     * @return bool
     */
    public function hasErrorMessage($messageString);

    /**
     * @param array $errorMessages
     * @return $this
     */
    public function addErrorMessages(array $errorMessages);

    /**
     * @param Message $errorMessage
     * @return $this
     */
    public function addErrorMessage(Message $errorMessage);

    /**
     * @return Message[]
     */
    public function getMessages();

    /**
     * @param string $messageString
     * @return bool
     */
    public function hasMessage($messageString);

    /**
     * @param Message $message
     * @return $this
     */
    public function addMessage(Message $message);

    /**
     * @param array $messages
     * @return $this
     */
    public function addMessages(array $messages);

    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @param bool $success
     * @return $this
     */
    public function setSuccess($success);

    /**
     * @return TransferInterface
     */
    public function getTransfer();

    /**
     * @param TransferInterface $transferObject
     * @return $this
     */
    public function setTransfer(TransferInterface $transferObject);
}
