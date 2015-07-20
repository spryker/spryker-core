<?php

namespace spec\SprykerFeature\Zed\Wishlist\Communication\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Silex\Application;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;


class GatewayControllerSpec extends ObjectBehavior
{
    function let(Application $app, Factory $factory)
    {
        $locator = Locator::getInstance();
        $this->beConstructedWith($app, $factory, $locator);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Zed\Wishlist\Communication\Controller\GatewayController');
    }

    function it_extends_abstract()
    {
        $this->shouldHaveType('SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController');
    }
}
