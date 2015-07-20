<?php

namespace spec\SprykerFeature\Client\Wishlist\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;


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
        $stack = new \SplStack();
        $stack->push(new WishlistItemStruct(['sku'     => 146013,
            'added_at' => 1234567453,
            'concrete_product_id' => 123,
            'quantity' => 5,
            'wihk' => 1298]));


        $stack->push(new WishlistItemStruct(['sku'     => 146313,
            'sku'     => 142013,
            'added_at' => 1034567453,
            'concrete_product_id' => 125,
            'quantity' => 3,
            'wihk' => '12we']));

        $this->getWishlist()->shouldReturn($stack);
    }
}


class WishlistItemStruct extends \ArrayObject
{

}
