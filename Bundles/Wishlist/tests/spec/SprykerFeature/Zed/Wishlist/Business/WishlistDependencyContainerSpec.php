<?php

namespace spec\SprykerFeature\Zed\Wishlist\Business;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Wishlist\WishlistConfig;


class WishlistDependencyContainerSpec extends ObjectBehavior
{
    function let(FactoryInterface $factory, LocatorLocatorInterface $locator, WishlistConfig $config)
    {
        $this->beConstructedWith($factory, $locator, $config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Zed\Wishlist\Business\WishlistDependencyContainer');
    }

    function it_extends_abstract()
    {
        $this->shouldHaveType('SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer');
    }
}
