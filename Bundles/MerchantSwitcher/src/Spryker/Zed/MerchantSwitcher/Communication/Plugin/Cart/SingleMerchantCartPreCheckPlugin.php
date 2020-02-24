<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 * @method \Spryker\Zed\MerchantSwitcher\Communication\MerchantSwitcherCommunicationFactory getFactory()
 */
class SingleMerchantCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    protected const GLOSSARY_KEY_PRODUCT_IS_NOT_AVAILABLE = 'merchant_switcher.message.product_is_not_available';
    protected const GLOSSARY_PARAMETER_NAME = '%product_name%';
    protected const GLOSSARY_PARAMETER_SKU = '%sku%';

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

        $quoteMerchantReference = $cartChangeTransfer->getQuote()->getMerchantReference();

        $messageTransfers = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (
                $quoteMerchantReference
                && $itemTransfer->getMerchantReference()
                && $itemTransfer->getMerchantReference() !== $quoteMerchantReference
            ) {
                $messageTransfers[] = (new MessageTransfer())
                    ->setValue(static::GLOSSARY_KEY_PRODUCT_IS_NOT_AVAILABLE)
                    ->setParameters([
                        static::GLOSSARY_PARAMETER_NAME => $itemTransfer->getName(),
                        static::GLOSSARY_PARAMETER_SKU => $itemTransfer->getSku(),
                    ]);
            }
        }

        return (new CartPreCheckResponseTransfer())
            ->setMessages(new ArrayObject($messageTransfers))
            ->setIsSuccess(!$messageTransfers);
    }
}
