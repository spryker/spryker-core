<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToStoreFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class StoreTableExpander implements StoreTableExpanderInterface
{
    /**
     * @var string
     */
    protected const COL_CURRENCIES_TITLE = 'Currencies';

    /**
     * @var string
     */
    protected const COL_CURRENCIES = 'currencies';

    /**
     * @var \Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToStoreFacadeInterface
     */
    protected CurrencyGuiToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(CurrencyGuiToStoreFacadeInterface $storeFacade)
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
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $config;
        }

        $configHeader = $config->getHeader() + [
                static::COL_CURRENCIES => static::COL_CURRENCIES_TITLE,
            ];
        $config->setHeader($configHeader);

        $config->addRawColumn(static::COL_CURRENCIES);

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
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $storeDataItem;
        }

        $currencies = [];
        $defaultCurrencyCode = $storeTransfer->getDefaultCurrencyIsoCode();
        foreach ($storeTransfer->getAvailableCurrencyIsoCodes() as $currencyIsoCode) {
            if ($currencyIsoCode === $defaultCurrencyCode) {
                $currencies[] = '<b>' . $currencyIsoCode . '</b>';

                continue;
            }
            $currencies[] = $currencyIsoCode;
        }

        return $storeDataItem + [
            static::COL_CURRENCIES => implode(', ', $currencies),
        ];
    }
}
