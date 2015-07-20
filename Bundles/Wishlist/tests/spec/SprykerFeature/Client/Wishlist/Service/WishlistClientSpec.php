<?php

namespace spec\SprykerFeature\Client\Wishlist\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\WishlistTransfer;


class WishlistClientSpec extends ObjectBehavior
{
    function let(FactoryInterface $factory, LocatorLocatorInterface $locator)
    {
        $this->beConstructedWith($factory, $locator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Client\Wishlist\Service\WishlistClient');
    }

    function it_extends_abstract()
    {
        $this->shouldHaveType('SprykerEngine\Client\Kernel\Service\AbstractClient');
    }

    function it_calls_getwishlist_method_and_brings_wishlistdata()
    {
        $this->getWishlist()->shouldBeAnInstanceOf(WishlistTransfer::class);
    }
}


class WishlistItemStruct extends \ArrayObject
{

}
