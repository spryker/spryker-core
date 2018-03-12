<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class MultiCartClient extends AbstractClient implements MultiCartClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createCart(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createZedMultiCartStub()->createQuote($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateCart(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createZedMultiCartStub()->updateQuote($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setActiveCart(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        // TODO: Implement setCartActive() method.
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getActiveCart(): QuoteTransfer
    {
        return $this->getFactory()->getQuoteClient()->getQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteCart(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createZedMultiCartStub()->deleteQuote($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer
    {
        return $this->getFactory()->createMultiCartSession()->getQuoteCollection();
    }
}
