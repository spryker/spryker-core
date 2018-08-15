<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConfig;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface;

class StoreCurrencyFinder implements StoreCurrencyFinderInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade,
        MinimumOrderValueGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransferFromRequest(?string $storeCurrencyRequestParam): CurrencyTransfer
    {
        if (!$storeCurrencyRequestParam) {
            return $this->currencyFacade->getCurrent();
        }

        list($storeName, $currencyCode) = explode(
            MinimumOrderValueGuiConfig::STORE_CURRENCY_DELIMITER,
            $storeCurrencyRequestParam
        );

        return $this->currencyFacade->fromIsoCode($currencyCode);
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransferFromRequest(?string $storeCurrencyRequestParam): StoreTransfer
    {
        if (!$storeCurrencyRequestParam) {
            return $this->storeFacade->getCurrentStore();
        }

        list($storeName, $currencyCode) = explode(
            MinimumOrderValueGuiConfig::STORE_CURRENCY_DELIMITER,
            $storeCurrencyRequestParam
        );

        return $this->storeFacade->getStoreByName($storeName);
    }
}
