<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\ZedRequest\Plugin\ServiceProvider;

use PHPUnit_Framework_TestCase;
use Silex\Application;
use Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer;
use Spryker\Yves\ZedRequest\Plugin\ServiceProvider\ZedRequestLogServiceProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group ZedRequest
 * @group Plugin
 * @group ServiceProvider
 * @group ZedRequestLogServiceProviderTest
 */
class ZedRequestLogServiceProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRegisterShouldDoNothing()
    {
        $serviceProvider = new ZedRequestLogServiceProvider();
        $serviceProvider->register(new Application());
    }

    /**
     * @return void
     */
    public function testBootShouldAddGuzzleLogMiddleware()
    {
        $application = new Application();

        $serviceProvider = new ZedRequestLogServiceProvider();
        $serviceProvider->boot($application);

        $handlerStackContainer = new HandlerStackContainer();

        $this->assertTrue($handlerStackContainer->getHandlerStack()->hasHandler());
    }

}
