<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service;

use Generated\Client\Ide\FactoryAutoCompletion\CartService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Cart\Service\Zed\CartStubInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method CartService getFactory()
 */
class CartDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return SessionInterface
     */
    public function createSession()
    {
        return $this->getFactory()->createSessionCartSession($this->createSessionClient());
    }

    /**
     * @return CartStubInterface
     */
    public function createZedStub()
    {
        return $this->getFactory()->createZedCartStub($this->createZedRequestClient());
    }

}
