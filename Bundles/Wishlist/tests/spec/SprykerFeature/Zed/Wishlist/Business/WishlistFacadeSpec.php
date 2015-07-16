<?php

namespace spec\SprykerFeature\Zed\Wishlist\Business;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Zed\Kernel\Locator;

class WishlistFacadeSpec extends ObjectBehavior
{
    function let(FactoryInterface $factory)
    {
        $this->beConstructedWith($factory, Locator::getInstance());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Zed\Wishlist\Business\WishlistFacade');
    }

    function it_extends()
    {
        $this->shouldHaveType('SprykerEngine\Zed\Kernel\Business\AbstractFacade');
    }
}
