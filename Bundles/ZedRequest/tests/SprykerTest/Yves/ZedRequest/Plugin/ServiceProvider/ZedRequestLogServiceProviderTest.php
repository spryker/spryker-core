<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\ZedRequest\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer;
use Spryker\Yves\ZedRequest\Plugin\ServiceProvider\ZedRequestLogServiceProvider;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group ZedRequest
 * @group Plugin
 * @group ServiceProvider
 * @group ZedRequestLogServiceProviderTest
 * Add your own group annotations below this line
 */
class ZedRequestLogServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testRegisterShouldDoNothing(): void
    {
        $serviceProvider = new ZedRequestLogServiceProvider();
        $serviceProvider->register(new Application());
    }

    /**
     * @return void
     */
    public function testBootShouldAddGuzzleLogMiddleware(): void
    {
        $application = new Application();

        $serviceProvider = new ZedRequestLogServiceProvider();
        $serviceProvider->boot($application);

        $handlerStackContainer = new HandlerStackContainer();

        $this->assertTrue($handlerStackContainer->getHandlerStack()->hasHandler());
    }
}
