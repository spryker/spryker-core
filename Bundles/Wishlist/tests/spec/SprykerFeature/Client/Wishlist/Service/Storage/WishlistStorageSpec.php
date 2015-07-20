<?php

namespace spec\SprykerFeature\Client\Wishlist\Service\Storage;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WishlistStorageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('SprykerFeature\Client\Wishlist\Service\Storage\WishlistStorage');
    }

    function it_extends_abstract()
    {
        $this->shouldHaveType('SprykerFeature\Shared\ProductFrontendExporterConnector\Code\KeyBuilder\SharedAbstractProductResourceKeyBuilder');
    }
}
