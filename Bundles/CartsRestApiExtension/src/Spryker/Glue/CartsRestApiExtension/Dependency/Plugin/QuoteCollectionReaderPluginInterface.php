<?php

namespace Spryker\Glue\CartsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;

interface QuoteCollectionReaderPluginInterface
{
    /**
     * Specification:
     * - This plugin interface is used for extend cart reader in cart module.
     * - Method provides quote collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer;
}
