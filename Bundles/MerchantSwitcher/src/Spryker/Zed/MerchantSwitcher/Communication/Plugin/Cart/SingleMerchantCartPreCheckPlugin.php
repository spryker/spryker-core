<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 * @method \Spryker\Zed\MerchantSwitcher\Communication\MerchantSwitcherCommunicationFactory getFactory()
 */
class SingleMerchantCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Goes through CartChangeTransfer.quoteTransfer.items and compares ItemTransfer.merchantReference with CartChangeTransfer.quoteTransfer.merchantReference.
     * - If values are not equal the plugin returns a failure response with an error message inside.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);

        if (!$this->getConfig()->isMerchantSwitcherEnabled()) {
            return $cartPreCheckResponseTransfer;
        }

        $singleMerchantQuoteValidationRequestTransfer = (new SingleMerchantQuoteValidationRequestTransfer())
            ->setItems($cartChangeTransfer->getItems())
            ->setMerchantReference($cartChangeTransfer->getQuote()->getMerchantReference());

        $singleMerchantQuoteValidationResponseTransfer = $this->getFacade()
            ->validateMerchantInQuoteItems($singleMerchantQuoteValidationRequestTransfer);

        return (new CartPreCheckResponseTransfer())
            ->setMessages($singleMerchantQuoteValidationResponseTransfer->getErrors())
            ->setIsSuccess($singleMerchantQuoteValidationResponseTransfer->getIsSuccessful());
    }
}
