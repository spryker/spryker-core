<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\CartsRestApi;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacade getFacade()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class SharedCartQuoteCollectionExpanderPlugin extends AbstractPlugin implements QuoteCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands quotes collection response with customer's shared quotes collection.
     * - Expands each quote with the QuotePermissionGroup the user has assigned to him.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function expandQuoteCollection(
        QuoteCollectionTransfer $quoteCollectionTransfer,
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer {
        return $this->getFacade()
            ->expandQuoteCollectionWithCustomerSharedQuoteCollection($quoteCollectionTransfer, $quoteCriteriaFilterTransfer);
    }
}
