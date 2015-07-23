<?php

namespace spec\SprykerFeature\Client\Wishlist\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SprykerEngine\Client\Kernel\Service\Factory;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class WishlistDependencyContainerSpec extends ObjectBehavior
{
    function let(Factory $factory, LocatorLocatorInterface $locator)
    {
        $this->beConstructedWith($factory, $locator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Client\Wishlist\Service\WishlistDependencyContainer');
    }

    function it_extends_abstract()
    {
        $this->shouldHaveType('SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer');
    }
}
