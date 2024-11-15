<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Plugin\CartReorder;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 * @method \Spryker\Zed\PersistentCart\Communication\PersistentCartCommunicationFactory getFactory()
 */
class PersistentCartReorderQuoteProviderStrategyPlugin extends AbstractPlugin implements CartReorderQuoteProviderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the quote is present in the `CartReorderRequestTransfer`.
     * - Checks if the quote has an ID.
     * - Checks if the quote has a customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CartReorderRequestTransfer $cartReorderRequestTransfer): bool
    {
        return $cartReorderRequestTransfer->getQuote()
            && $cartReorderRequestTransfer->getQuoteOrFail()->getIdQuote()
            && $cartReorderRequestTransfer->getQuoteOrFail()->getCustomer();
    }

    /**
     * {@inheritDoc}
     * - Finds the quote by the provided quote ID and customer.
     * - Removes items from the found quote.
     * - Returns the found quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(CartReorderRequestTransfer $cartReorderRequestTransfer): QuoteTransfer
    {
        $quoteTransfer = $this->getFacade()
            ->findQuote(
                $cartReorderRequestTransfer->getQuoteOrFail()->getIdQuoteOrFail(),
                $cartReorderRequestTransfer->getQuoteOrFail()->getCustomerOrFail(),
            )
            ->getQuoteTransferOrFail();

        $quoteTransfer->setItems(new ArrayObject());

        return $quoteTransfer;
    }
}
