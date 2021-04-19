<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StockCollectionTransfer;

/**
 * Implement this plugin interface to expand stock collection during the fetching stock collection data from the database.
 */
interface StockCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands a {@link \Generated\Shared\Transfer\StockCollectionTransfer} with additional data fields.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function expandStockCollection(StockCollectionTransfer $stockCollectionTransfer): StockCollectionTransfer;
}
