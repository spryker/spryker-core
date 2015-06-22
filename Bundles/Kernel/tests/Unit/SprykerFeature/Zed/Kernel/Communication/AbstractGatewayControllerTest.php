<?php

namespace Unit\SprykerFeature\Zed\Kernel\Communication;

use Silex\Application;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use Unit\SprykerFeature\Zed\Kernel\Communication\Fixtures\FixtureGatewayController;

class AbstractGatewayControllerTest extends \PHPUnit_Framework_TestCase
{

    public function testGatewayControllerMustBeConstructable()
    {
        $this->markTestSkipped('AbstractController are instantiating Plugins which is bad and will be removed soon, after remove of the messenger there this test is runnning');

        $application = new Application();
        $factory = new Factory('Kernel');
        $locator = Locator::getInstance();

        $this->assertInstanceOf(
            'SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController',
            new FixtureGatewayController($application, $factory, $locator)
        );
    }

}
