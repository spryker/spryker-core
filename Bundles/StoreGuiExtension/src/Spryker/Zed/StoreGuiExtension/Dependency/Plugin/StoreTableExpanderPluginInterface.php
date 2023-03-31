<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

/**
 * Allows to extend the store table {@link \Spryker\Zed\StoreGui\Communication\Table\StoreTable}
 */
interface StoreTableExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands config store table in Zed.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration;

    /**
     * Specification:
     * - Expands StoreTransfers with additional data.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfers(array $storeTransfers): array;

    /**
     * Specification:
     * - Expands table data rows of store table in Zed.
     *
     * @api
     *
     * @param array<mixed> $storeDataItem
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<mixed>
     */
    public function expandDataItem(array $storeDataItem, StoreTransfer $storeTransfer): array;
}
