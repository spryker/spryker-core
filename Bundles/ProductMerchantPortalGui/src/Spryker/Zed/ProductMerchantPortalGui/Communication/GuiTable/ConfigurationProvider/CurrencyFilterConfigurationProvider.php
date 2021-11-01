<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;

class CurrencyFilterConfigurationProvider implements CurrencyFilterConfigurationProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return array<int, string>
     */
    public function getCurrencyOptions(): array
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        $currencyOptions = [];
        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $currencyOptions[(int)$currencyTransfer->getIdCurrency()] = $currencyTransfer->getCodeOrFail();
            }
        }

        return $currencyOptions;
    }
}
