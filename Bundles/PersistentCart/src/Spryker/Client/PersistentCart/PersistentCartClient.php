<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 */
class PersistentCartClient extends AbstractClient implements PersistentCartClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteDeleter()->deleteQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteCreator()->createQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteWithReloadedItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteCreator()->createQuoteWithReloadedItems($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteUpdater()->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteByCustomerAndStore(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createZedPersistentCartStub()->replaceQuoteByCustomerAndStore($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function persistQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteWriter()->persist($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return string
     */
    public function generateGuestCartCustomerReference(string $customerReference): string
    {
        return $this->getFactory()
            ->createGuestCartCustomerReferenceGenerator()
            ->generateGuestCartCustomerReference($customerReference);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function reloadQuoteForCustomer(CustomerTransfer $customerTransfer): void
    {
        $this->getFactory()
            ->createCustomerQuoteCleaner()
            ->reloadQuoteForCustomer($customerTransfer);
    }
}
