<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToStoreFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig;

class StoreCurrencyFinder implements StoreCurrencyFinderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade,
        MerchantRelationshipMinimumOrderValueGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransferFromRequestParam(?string $storeCurrencyRequestParam): CurrencyTransfer
    {
        if (!$storeCurrencyRequestParam) {
            return $this->currencyFacade->getCurrent();
        }

        $currencyCode = array_pop(explode(
            MerchantRelationshipMinimumOrderValueGuiConfig::STORE_CURRENCY_DELIMITER,
            $storeCurrencyRequestParam
        ));

        return $this->currencyFacade->fromIsoCode($currencyCode);
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransferFromRequestParam(?string $storeCurrencyRequestParam): StoreTransfer
    {
        if (!$storeCurrencyRequestParam) {
            return $this->storeFacade->getCurrentStore();
        }

        $storeName = array_shift(explode(
            MerchantRelationshipMinimumOrderValueGuiConfig::STORE_CURRENCY_DELIMITER,
            $storeCurrencyRequestParam
        ));

        return $this->storeFacade->getStoreByName($storeName);
    }
}
