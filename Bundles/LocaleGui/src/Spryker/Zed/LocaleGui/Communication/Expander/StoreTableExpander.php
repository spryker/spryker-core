<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToStoreFacadeInterface;

class StoreTableExpander implements StoreTableExpanderInterface
{
    /**
     * @var string
     */
    protected const COL_LOCALES_TITLE = 'Locales';

    /**
     * @var string
     */
    protected const COL_LOCALES = 'locales';

    protected LocaleGuiToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(LocaleGuiToStoreFacadeInterface $storeFacade)
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
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $config;
        }

        $configHeader = $config->getHeader() + [
                static::COL_LOCALES => static::COL_LOCALES_TITLE,
            ];
        $config->setHeader($configHeader);

        $config->addRawColumn(static::COL_LOCALES);

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
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $storeDataItem;
        }

        $localeCodes = [];
        $defaultLocaleCode = $storeTransfer->getDefaultLocaleIsoCode();
        foreach ($storeTransfer->getAvailableLocaleIsoCodes() as $localeIsoCode) {
            if ($localeIsoCode === $defaultLocaleCode) {
                $localeCodes[] = '<b>' . $localeIsoCode . '</b>';

                continue;
            }
            $localeCodes[] = $localeIsoCode;
        }

        return $storeDataItem + [
            static::COL_LOCALES => implode(', ', $localeCodes),
        ];
    }
}
