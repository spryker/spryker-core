<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use Spryker\Client\Cart\Session\QuoteSession;
use Spryker\Client\Cart\Zed\CartStub;
use Spryker\Client\Kernel\AbstractFactory;

class CartFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Cart\Session\QuoteSessionInterface
     */
    public function createSession()
    {
        return new QuoteSession($this->getSessionClient());
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    public function createZedStub()
    {
        return new CartStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_ZED_REQUEST);
    }

}
