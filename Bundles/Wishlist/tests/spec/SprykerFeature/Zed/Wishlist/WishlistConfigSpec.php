<?php

namespace spec\SprykerFeature\Zed\Wishlist;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;


class WishlistConfigSpec extends ObjectBehavior
{
    function let(Config $config, LocatorLocatorInterface $locator)
    {
        $this->beConstructedWith($config, $locator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Zed\Wishlist\WishlistConfig');
    }

    function it_extends_abstract()
    {
        $this->shouldHaveType('SprykerEngine\Zed\Kernel\AbstractBundleConfig');
    }
}
