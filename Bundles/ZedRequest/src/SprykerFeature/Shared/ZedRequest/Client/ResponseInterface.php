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
    public function getInfoMessages();

    /**
     * @param string $messageString
     *
     * @return bool
     */
    public function hasInfoMessage($messageString);

    /**
     * @param Message $message
     *
     * @return self
     */
    public function addInfoMessage(Message $message);

    /**
     * @param array $messages
     *
     * @return self
     */
    public function addInfoMessages(array $messages);

    /**
     * @return Message[]
     */
    public function getSuccessMessages();

    /**
     * @param string $messageString
     *
     * @return bool
     */
    public function hasSuccessMessage($messageString);

    /**
     * @param array $successMessages
     *
     * @return $this
     */
    public function addSuccessMessages(array $successMessages);

    /**
     * @param Message $successMessage
     *
     * @return $this
     */
    public function addSuccessMessage(Message $successMessage);

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
