<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Helper;

use Codeception\Lib\Framework;
use Spryker\Client\Session\SessionClient;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Spryker\Service\Container\Container;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Symfony\Component\HttpFoundation\Session\Session;

class GlueBootstrap extends Framework
{
    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @return void
     */
    public function _initialize(): void
    {
        $this->boot();
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            static::SERVICE_RESOURCE_BUILDER => new RestResourceBuilder(),
        ]));

        (new SessionClient())->setContainer(
            new Session(
                new MockArraySessionStorage()
            )
        );
    }
}
