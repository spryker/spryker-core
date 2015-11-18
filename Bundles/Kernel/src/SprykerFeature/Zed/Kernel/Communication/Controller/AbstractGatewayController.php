<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Kernel\Communication\Controller;

use Silex\Application;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\ZedRequest\Client\Message;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\NewRelic\Api;

abstract class AbstractGatewayController extends AbstractController
{

    const MESSAGE_KEY = 'message';

    const DATA_KEY = 'data';

    /**
     * @var Message[]
     */
    protected $messages = [];

    /**
     * @var Message[]
     */
    protected $errorMessages = [];

    /**
     * @var bool
     */
    protected $success = true;

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
     * @return Message[]
     */
    public function getMessages()
    {
        return $this->messages;
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
}
