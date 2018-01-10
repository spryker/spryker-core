<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface;

class StoreMapper implements StoreMapperInterface
{
    /**
     * @var \Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface;
     */
    protected $storeConfigurationProvider;

    /**
     * @param \Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface $storeConfigurationProvider
     */
    public function __construct(StoreConfigurationProviderInterface $storeConfigurationProvider)
    {
        $this->storeConfigurationProvider = $storeConfigurationProvider;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapEntityToTransfer(SpyStore $storeEntity)
    {
        $storeName = $storeEntity->getName();

        $currencyTransfer = (new StoreTransfer())
            ->setSharedPersistenceWithStores($this->storeConfigurationProvider->getSharedPersistenceWithStores())
            ->setSelectedCurrencyIsoCode($this->storeConfigurationProvider->getCurrentStoreSelectedCurrencyIsoCode())
            ->setDefaultCurrencyIsoCode($this->storeConfigurationProvider->getDefaultCurrencyFor($storeName))
            ->setAvailableCurrencyIsoCodes($this->storeConfigurationProvider->getAvailableCurrenciesFor($storeName))
            ->setAvailableLocaleIsoCodes($this->storeConfigurationProvider->getAvailableLocaleIsoCodesFor($storeName));

        return $currencyTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Store\Persistence\SpyStore
     */
    public function mapTransferToEntity(SpyStore $storeEntity, StoreTransfer $storeTransfer)
    {
        $storeEntity->fromArray($storeTransfer->toArray());

        return $storeEntity;
    }
}
