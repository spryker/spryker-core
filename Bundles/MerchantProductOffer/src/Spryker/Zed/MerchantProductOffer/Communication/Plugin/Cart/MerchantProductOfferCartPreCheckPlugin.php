<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Communication\Plugin\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface getFacade()
 */
class MerchantProductOfferCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if cart change transfer has product offers with inactive merchants.
     * - Returns unsuccessful response with error messages, if cart change transfer has product offers with inactive merchants.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $messageTransfers = $this->getFacade()
            ->validateItems($cartChangeTransfer->getItems()->getArrayCopy());

        return (new CartPreCheckResponseTransfer())
            ->setMessages(new ArrayObject($messageTransfers))
            ->setIsSuccess(!$messageTransfers);
    }
}
