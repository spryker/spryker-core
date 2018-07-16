<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Glue\GlueApplication\Bootstrap;

use Spryker\Client\Session\SessionClient;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Spryker\Glue\Kernel\Application;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractGlueBootstrap
{
    /**
     * @var \Spryker\Glue\Kernel\Application
     */
    protected $application;

    public function __construct()
    {
        $this->application = new Application();

        $this->setUpSession();
    }

    /**
     * @return \Spryker\Glue\Kernel\Application
     */
    public function boot(): Application
    {
        $this->registerServiceProviders();

        return $this->application;
    }

    /**
     * @return void
     */
    abstract protected function registerServiceProviders(): void;

    /**
     * @return void
     */
    protected function setUpSession(): void
    {
        (new SessionClient())->setContainer(
            new Session(
                new MockArraySessionStorage()
            )
        );
    }
}
