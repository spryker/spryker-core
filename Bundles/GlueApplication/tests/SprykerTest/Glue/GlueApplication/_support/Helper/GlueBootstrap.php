<?php

namespace SprykerTest\Glue\GlueApplication\Helper;

use Codeception\Lib\Framework;
use Spryker\Client\Session\SessionClient;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Spryker\Glue\Kernel\Application;
use Spryker\Glue\Kernel\Plugin\Pimple;
use Symfony\Component\HttpFoundation\Session\Session;

class GlueBootstrap extends Framework
{
    /**
     * @return void
     */
    public function _initialize()
    {
        $this->boot();
    }

    /**
     * @return mixed
     */
    public function boot()
    {
        $pimplePlugin = new Pimple();
        $pimplePlugin->setApplication(new Application());

        $pimplePlugin->getApplication()['resource_builder'] = new RestResourceBuilder();

        (new SessionClient())->setContainer(
            new Session(
                new MockArraySessionStorage()
            )
        );
    }
}
