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
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function createSession()
    {
        return new QuoteSession($this->getSessionClient());
    }

    /**
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    public function createZedStub()
    {
        return new CartStub($this->getZedRequestClient());
    }

}
