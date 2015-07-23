<?php

namespace spec\SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Transfer\WishlistItemTransfer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\WishlistTransfer;


class WishlistClientSpec extends ObjectBehavior
{
    public function let(FactoryInterface $factory, LocatorLocatorInterface $locator)
    {
        $this->beConstructedWith($factory, $locator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Client\Wishlist\Service\WishlistClient');
    }

    public function it_extends_abstract()
    {
        $this->shouldHaveType('SprykerEngine\Client\Kernel\Service\AbstractClient');
    }


    public function it_calls_add_item_method()
    {
        //$this->addItem(new WishlistItemTransfer());
    }


}


