<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 */
class SingleMerchantCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    protected const GLOSSARY_KEY_PRODUCT_IS_NOT_AVAILABLE = 'merchant_switcher.message.product_is_not_available';
    protected const GLOSSARY_PARAMETER_NAME = '%product_name%';

    /**
     * {@inheritDoc}
     * - Goes through QuoteTransfer.Items and compares ItemTransfer.merchantReference with QuoteTransfer.merchantReference.
     * - If values are not equal the plugin returns a failure response and add an error flash message.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();

        $merchantReference = $cartChangeTransfer->getQuote()->getMerchantReference();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($merchantReference && $itemTransfer->getMerchantReference() && $itemTransfer->getMerchantReference() !== $merchantReference) {
                $messageTransfer = (new MessageTransfer())
                    ->setValue(static::GLOSSARY_KEY_PRODUCT_IS_NOT_AVAILABLE)
                    ->setParameters([
                        static::GLOSSARY_PARAMETER_NAME => $itemTransfer->getName(),
                    ]);

                $cartPreCheckResponseTransfer->addMessage($messageTransfer);
                $cartPreCheckResponseTransfer->setIsSuccess(false);
            }
        }

        return $cartPreCheckResponseTransfer;
    }
}
