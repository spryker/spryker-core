<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToStoreFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class StoreTableExpander implements StoreTableExpanderInterface
{
    /**
     * @var string
     */
    protected const COL_COUNTRIES_TITLE = 'Delivery regions';

    /**
     * @var string
     */
    protected const COL_COUNTRIES = 'countries';

    /**
     * @var \Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToStoreFacadeInterface
     */
    protected CountryGuiToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(CountryGuiToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        /* Required by infrastructure, exists only for BC reasons with DMS mode. */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $config;
        }

        $configHeader = $config->getHeader() + [
                static::COL_COUNTRIES => static::COL_COUNTRIES_TITLE,
            ];
        $config->setHeader($configHeader);

        return $config;
    }

    /**
     * @param array<mixed> $storeDataItem
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<mixed>
     */
    public function expandDataItem(array $storeDataItem, StoreTransfer $storeTransfer): array
    {
        /* Required by infrastructure, exists only for BC reasons with DMS mode. */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $storeDataItem;
        }

        return $storeDataItem + [
            static::COL_COUNTRIES => implode(', ', $storeTransfer->getCountryNames()),
        ];
    }
}
