<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GiftCard;

use Spryker\Client\GiftCard\CartCode\GiftCardCartCode;
use Spryker\Client\GiftCard\CartCode\GiftCardCartCodeInterface;
use Spryker\Client\Kernel\AbstractFactory;

class GiftCardFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\GiftCard\CartCode\GiftCardCartCodeInterface
     */
    public function createGiftCardCartCode(): GiftCardCartCodeInterface
    {
        return new GiftCardCartCode();
    }
}
