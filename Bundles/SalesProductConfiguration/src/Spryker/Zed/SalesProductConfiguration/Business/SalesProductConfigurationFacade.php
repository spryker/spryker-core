<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Business;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesProductConfiguration\Business\SalesProductConfigurationBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationRepositoryInterface getRepository()
 */
class SalesProductConfigurationFacade extends AbstractFacade implements SalesProductConfigurationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemConfigurationsFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createSalesOrderItemConfigurationWriter()
            ->saveSalesOrderItemConfigurationsFromQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithProductConfiguration(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createOrderItemExpander()
            ->expandOrderItemsWithProductConfiguration($itemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateCartReorderItemsWithProductConfiguration(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        return $this->getFactory()
            ->createCartReorderItemHydrator()
            ->hydrate($cartReorderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer $salesOrderItemConfigurationCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionResponseTransfer
     */
    public function deleteSalesOrderItemConfigurationCollection(
        SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer $salesOrderItemConfigurationCollectionDeleteCriteriaTransfer
    ): SalesOrderItemConfigurationCollectionResponseTransfer {
        return $this->getFactory()
            ->createSalesOrderItemConfigurationDeleter()
            ->deleteSalesOrderItemConfigurationCollection($salesOrderItemConfigurationCollectionDeleteCriteriaTransfer);
    }
}
