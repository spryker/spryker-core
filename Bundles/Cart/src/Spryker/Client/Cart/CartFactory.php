<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart;

use Spryker\Client\Cart\Session\QuoteSession;
use Spryker\Client\Cart\Zed\CartStub;
use Spryker\Client\Cart\Zed\CartStubInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CartFactory extends AbstractFactory
{

    /**
     * @return SessionInterface
     */
    public function createSession()
    {
        return new QuoteSession($this->createSessionClient());
    }

    /**
     * @return CartStubInterface
     */
    public function createZedStub()
    {
        return new CartStub($this->createZedRequestClient());
    }

}
