<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;

abstract class AbstractGatewayController extends AbstractController
{
    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var \Generated\Shared\Transfer\MessageTransfer[]
     */
    private $errorMessages = [];

    /**
     * @var \Generated\Shared\Transfer\MessageTransfer[]
     */
    private $infoMessages = [];

    /**
     * @var \Generated\Shared\Transfer\MessageTransfer[]
     */
    private $successMessages = [];

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return $this
     */
    protected function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return $this
     */
    protected function addInfoMessage($message, array $data = [])
    {
        $messageObject = new MessageTransfer();
        $messageObject->setValue($message);
        $messageObject->setParameters($data);

        $this->infoMessages[] = $messageObject;

        return $this;
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return $this
     */
    protected function addErrorMessage($message, array $data = [])
    {
        $messageObject = new MessageTransfer();
        $messageObject->setValue($message);
        $messageObject->setParameters($data);

        $this->errorMessages[] = $messageObject;

        return $this;
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return $this
     */
    protected function addSuccessMessage($message, array $data = [])
    {
        $messageObject = new MessageTransfer();
        $messageObject->setValue($message);
        $messageObject->setParameters($data);

        $this->successMessages[] = $messageObject;

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getInfoMessages()
    {
        return $this->infoMessages;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getSuccessMessages()
    {
        return $this->successMessages;
    }
}
