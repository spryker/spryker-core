<?php

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
