<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication\Controller;

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

    public function __construct()
    {
        // @todo this can be a plugin which listen for kernel.controller events
        $newRelicApi = new Api();
        $newRelicApi->addCustomParameter('Call_from', 'Yves');
    }

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
