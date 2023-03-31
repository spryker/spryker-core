<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\StoreConfig;

class DynamicStoreExpander implements StoreExpanderInterface
{
    /**
     * @var array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface>
     */
    protected $storeCollectionExpanderPlugins;

    /**
     * @var \Spryker\Zed\Store\StoreConfig
     */
    protected StoreConfig $config;

    /**
     * @param array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface> $storeCollectionExpanderPlugins
     * @param \Spryker\Zed\Store\StoreConfig $config
     */
    public function __construct(array $storeCollectionExpanderPlugins, StoreConfig $config)
    {
        $this->storeCollectionExpanderPlugins = $storeCollectionExpanderPlugins;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function expandStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        /** @phpstan-var non-empty-array<int, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers */
        $storeTransfers = $this->executeStoreCollectionExpanderPlugins([$storeTransfer]);

        return reset($storeTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStores(array $storeTransfers): array
    {
        return $this->executeStoreCollectionExpanderPlugins($storeTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function executeStoreCollectionExpanderPlugins(array $storeTransfers): array
    {
        $storeTransfers = $this->expandWithDefaultTimezone($storeTransfers);

        foreach ($this->storeCollectionExpanderPlugins as $storeCollectionExpanderPlugin) {
            $storeTransfers = $storeCollectionExpanderPlugin->expand($storeTransfers);
        }

        return $storeTransfers;
    }

    /**
     * @deprecated Exists for back compatibility reasons only.
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function expandWithDefaultTimezone(array $storeTransfers): array
    {
        foreach ($storeTransfers as $storeTransfer) {
            $defaultTimezone = $this->config->getDefaultTimezone();
            $storeTransfer->setTimezone($defaultTimezone);
        }

        return $storeTransfers;
    }
}
