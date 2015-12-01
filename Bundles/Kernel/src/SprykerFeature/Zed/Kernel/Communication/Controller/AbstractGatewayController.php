<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Kernel\Communication\Controller;

use Silex\Application;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\NewRelic\Api;
use SprykerFeature\Shared\ZedRequest\Client\Message;

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

    /**
     * @param Application $application
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Application $application, Factory $factory, Locator $locator)
    {
        parent::__construct($application, $factory, $locator);

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
     *
     * @return self
     */
    protected function addInfoMessage($message)
    {
        $messageObject = new Message();
        $messageObject->setMessage($message);

        $this->infoMessages[] = $messageObject;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    protected function addErrorMessage($message)
    {
        $messageObject = new Message();
        $messageObject->setMessage($message);

        $this->errorMessages[] = $messageObject;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    protected function addSuccessMessage($message)
    {
        $messageObject = new Message();
        $messageObject->setMessage($message);

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
