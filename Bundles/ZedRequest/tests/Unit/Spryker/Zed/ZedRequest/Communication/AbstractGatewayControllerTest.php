<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ZedRequest\Communication;

use PHPUnit_Framework_TestCase;
use Silex\Application;
use Spryker\Zed\ZedRequest\Communication\Controller\AbstractGatewayController;
use Unit\Spryker\Zed\ZedRequest\Communication\Fixture\FixtureGatewayController;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ZedRequest
 * @group Communication
 * @group AbstractGatewayControllerTest
 */
class AbstractGatewayControllerTest extends PHPUnit_Framework_TestCase
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
