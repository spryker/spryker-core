<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Zed\Store\Dependency\StoreToKernelStoreInterface;

class StoreMapper implements StoreMapperInterface
{

    /**
     * @var \Spryker\Zed\Store\Dependency\StoreToKernelStoreInterface
     */
    protected $storeConfigurationProvider;

    /**
     * @param \Spryker\Zed\Store\Dependency\StoreToKernelStoreInterface $storeConfigurationProvider
     */
    public function __construct(StoreToKernelStoreInterface $storeConfigurationProvider)
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
        $currencyTransfer = (new StoreTransfer())
            ->setCurrencyIsoCode($this->storeConfigurationProvider->getStoreName())
            ->setAvailableCurrencyIsoCodes($this->storeConfigurationProvider->getCurrencyIsoCodes())
            ->setSelectedLocaleIsoCode($this->storeConfigurationProvider->getCurrentLocale())
            ->setAvailableLocaleIsoCodes($this->storeConfigurationProvider->getLocales());

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
