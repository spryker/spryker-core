<?php

namespace Spryker\Zed\CartsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;

interface QuoteCollectionReaderPluginInterface
{
    /**
     * Specification:
     * - This plugin method is used to find quote collection in CartsRestApi module.
     * - Method provides quote collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getQuoteCollection(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
    ): QuoteCollectionResponseTransfer;
}
