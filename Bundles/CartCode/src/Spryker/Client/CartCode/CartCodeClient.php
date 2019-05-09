<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CartCode\CartCodeFactory getFactory()
 */
class CartCodeClient extends AbstractClient implements CartCodeClientInterface
{

    public function addCode(string $code)
    {
        $this->getFactory()->createCodeAdder()->add($code);
    }

    public function removeCode(string $code)
    {
        $this->getFactory()->createCodeRemover()->remove($code);
    }

    public function clearCodes()
    {
        $this->getFactory()->createCodeCleaner()->clear();
    }
}
