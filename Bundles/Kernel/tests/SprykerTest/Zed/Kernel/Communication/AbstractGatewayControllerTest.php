<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Communication;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerTest\Zed\Kernel\Communication\Fixture\FixtureGatewayController;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group AbstractGatewayControllerTest
 * Add your own group annotations below this line
 */
class AbstractGatewayControllerTest extends Unit
{
    /**
     * @return void
     */
    public function testGatewayControllerMustBeConstructable()
    {
        $application = new Application();

        $this->assertInstanceOf(
            AbstractGatewayController::class,
            new FixtureGatewayController($application)
        );
    }
}
