<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ResponseInterface
{
    const INFO_MESSAGES = 'infoMessages';
    const ERROR_MESSAGES = 'errorMessages';
    const SUCCESS_MESSAGES = 'successMessages';
    const SUCCESS = 'success';
    const TRANSFER_CLASSNAME = 'transferClassName';
    const TRANSFER = 'transfer';

    /**
     * @param array $values
     *
     * @return void
     */
    public function fromArray(array $values);

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
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
     * @return $this
     */
    public function addErrorMessages(array $errorMessages);

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $errorMessage
     *
     * @return $this
     */
    public function addErrorMessage(MessageTransfer $errorMessage);

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getInfoMessages();

    /**
     * @param string $messageString
     *
     * @return bool
     */
    public function hasInfoMessage($messageString);

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return $this
     */
    public function addInfoMessage(MessageTransfer $message);

    /**
     * @param array $messages
     *
     * @return $this
     */
    public function addInfoMessages(array $messages);

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
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
     * @param \Generated\Shared\Transfer\MessageTransfer $successMessage
     *
     * @return $this
     */
    public function addSuccessMessage(MessageTransfer $successMessage);

    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess($success);

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getTransfer();

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transferObject);
}
