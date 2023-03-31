<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\LocaleGui\Communication\LocaleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\LocaleGui\LocaleGuiConfig getConfig()
 */
class LocaleStoreTableExpanderPlugin extends AbstractPlugin implements StoreTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds locale header to Store table.
     * - Adds locale header only if `Dynamic Store` is enabled.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        return $this->getFactory()
            ->createStoreTableExpander()
            ->expandConfig($config);
    }

    /**
     * {@inheritDoc}
     * - Expands collection of store transfers with available locale codes.
     * - Expands store transfers only if `Dynamic Store` is enabled.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfers(array $storeTransfers): array
    {
        return $this->getFactory()
            ->getLocaleFacade()
            ->expandStoreTransfersWithLocales($storeTransfers);
    }

    /**
     * {@inheritDoc}
     * - Expands table data rows of store table with locale codes.
     * - Expands data only if `Dynamic Store` is enabled.
     *
     * @api
     *
     * @param array<mixed> $storeDataItem
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<mixed>
     */
    public function expandDataItem(array $storeDataItem, StoreTransfer $storeTransfer): array
    {
        return $this->getFactory()
            ->createStoreTableExpander()
            ->expandDataItem($storeDataItem, $storeTransfer);
    }
}
