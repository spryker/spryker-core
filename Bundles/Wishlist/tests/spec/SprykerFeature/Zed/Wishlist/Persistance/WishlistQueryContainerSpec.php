<?php

namespace spec\SprykerFeature\Zed\Wishlist\Persistance;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SprykerEngine\Zed\Kernel\Persistence\Factory;
use SprykerEngine\Zed\Kernel\Locator;

class WishlistQueryContainerSpec extends ObjectBehavior
{
    function let(Factory $factory)
    {
        $this->beConstructedWith($factory, Locator::getInstance());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Zed\Wishlist\Persistance\WishlistQueryContainer');
    }

    function it_extends_abstract()
    {
        $this->shouldHaveType('SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer');
    }
}
