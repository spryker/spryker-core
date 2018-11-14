<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Cart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;

class CartReader implements CartReaderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected $quoteCollectionReaderPlugin;

    /**
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface $quoteCollectionReaderPlugin
     */
    public function __construct(QuoteCollectionReaderPluginInterface $quoteCollectionReaderPlugin)
    {
        $this->quoteCollectionReaderPlugin = $quoteCollectionReaderPlugin;
    }

    /**
     * @param string $uuid
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByUuid(string $uuid, QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): ?QuoteTransfer
    {
        $quoteCollection = $this->quoteCollectionReaderPlugin->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);
        foreach ($quoteCollection->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getUuid() === $uuid) {
                return $quoteTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->quoteCollectionReaderPlugin->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);
    }
}
