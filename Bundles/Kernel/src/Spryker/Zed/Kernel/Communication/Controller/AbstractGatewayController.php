<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication\Controller;

use Silex\Application;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Shared\NewRelic\Api;
use Spryker\Shared\ZedRequest\Client\Message;

abstract class AbstractGatewayController extends AbstractController
{

    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var Message[]
     */
    private $errorMessages = [];

    /**
     * @var Message[]
     */
    private $infoMessages = [];

    /**
     * @var Message[]
     */
    private $successMessages = [];

    public function __construct()
    {
        // @todo this can be a plugin which listen for kernel.controller events
        $newRelicApi = new Api();
        $newRelicApi->addCustomParameter('Call_from', 'Yves');
    }

    /**
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
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
     * @return Message[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @return Message[]
     */
    public function getInfoMessages()
    {
        return $this->infoMessages;
    }

    /**
     * @return Message[]
     */
    public function getSuccessMessages()
    {
        return $this->successMessages;
    }

}
