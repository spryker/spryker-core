<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\Communication;

use Silex\Application;
use Unit\Spryker\Zed\Kernel\Communication\Fixtures\FixtureGatewayController;

class AbstractGatewayControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGatewayControllerMustBeConstructable()
    {
        $application = new Application();

        $this->assertInstanceOf(
            'Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController',
            new FixtureGatewayController($application)
        );
    }

}
