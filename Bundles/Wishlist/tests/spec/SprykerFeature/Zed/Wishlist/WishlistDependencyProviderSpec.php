<?php

namespace spec\SprykerFeature\Zed\Wishlist;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WishlistDependencyProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Zed\Wishlist\WishlistDependencyProvider');
    }

    function it_extends_abstract()
    {
        $this->shouldBeAnInstanceOf('SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider');
    }
}
