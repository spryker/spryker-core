<?php

namespace spec\SprykerFeature\Client\Wishlist\Service\Zed;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class WishlistStubSpec extends ObjectBehavior
{
    function let(ZedRequestClient $requset)
    {
        $this->beConstructedWith($requset);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Client\Wishlist\Service\Zed\WishlistStub');
    }
}
