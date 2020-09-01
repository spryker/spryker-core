<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Business;

use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductConfiguration(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createOrderItemExpander()
            ->expandOrderItemsWithProductConfiguration($itemTransfers);
    }
}
