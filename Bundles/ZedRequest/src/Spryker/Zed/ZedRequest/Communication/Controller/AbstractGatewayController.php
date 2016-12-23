<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication\Controller;

use Spryker\Shared\ZedRequest\Client\Message;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

abstract class AbstractGatewayController extends AbstractController
{

    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var \Spryker\Shared\ZedRequest\Client\Message[]
     */
    private $errorMessages = [];

    /**
     * @var \Spryker\Shared\ZedRequest\Client\Message[]
     */
    private $infoMessages = [];

    /**
     * @var \Spryker\Shared\ZedRequest\Client\Message[]
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
        $messageObject = new Message();
        $messageObject->setMessage($message);
        $messageObject->setData($data);

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
        $messageObject = new Message();
        $messageObject->setMessage($message);
        $messageObject->setData($data);

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
        $messageObject = new Message();
        $messageObject->setMessage($message);
        $messageObject->setData($data);

        $this->successMessages[] = $messageObject;

        return $this;
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getInfoMessages()
    {
        return $this->infoMessages;
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getSuccessMessages()
    {
        return $this->successMessages;
    }

}
