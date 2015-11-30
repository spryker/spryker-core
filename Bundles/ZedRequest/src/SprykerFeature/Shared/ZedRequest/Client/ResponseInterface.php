<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerEngine\Shared\Transfer\TransferInterface;

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
     *
     * @return bool
     */
    public function hasErrorMessage($messageString);

    /**
     * @param array $errorMessages
     *
     * @return self
     */
    public function addErrorMessages(array $errorMessages);

    /**
     * @param Message $errorMessage
     *
     * @return self
     */
    public function addErrorMessage(Message $errorMessage);

    /**
     * @return Message[]
     */
    public function getMessages();

    /**
     * @param string $messageString
     *
     * @return bool
     */
    public function hasMessage($messageString);

    /**
     * @param Message $message
     *
     * @return self
     */
    public function addMessage(Message $message);

    /**
     * @param array $messages
     *
     * @return self
     */
    public function addMessages(array $messages);

    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @param bool $success
     *
     * @return self
     */
    public function setSuccess($success);

    /**
     * @return TransferInterface
     */
    public function getTransfer();

    /**
     * @param TransferInterface $transferObject
     *
     * @return self
     */
    public function setTransfer(TransferInterface $transferObject);

}
