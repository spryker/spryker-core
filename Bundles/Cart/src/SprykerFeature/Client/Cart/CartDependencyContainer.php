<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart;

use Spryker\Client\Cart\Zed\CartStub;
use Spryker\Client\Cart\Session\CartSession;
use Spryker\Client\Kernel\AbstractDependencyContainer;
use Spryker\Client\Cart\Zed\CartStubInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return SessionInterface
     */
    public function createSession()
    {
        return new CartSession($this->createSessionClient());
    }

    /**
     * @return CartStubInterface
     */
    public function createZedStub()
    {
        return new CartStub($this->createZedRequestClient());
    }

}
