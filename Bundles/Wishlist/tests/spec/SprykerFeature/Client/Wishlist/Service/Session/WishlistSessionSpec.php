<?php

namespace spec\SprykerFeature\Client\Wishlist\Service\Session;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WishlistSessionSpec extends ObjectBehavior
{
    function let(SessionInterface $session)
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Client\Wishlist\Service\Session\WishlistSession');
    }


}
