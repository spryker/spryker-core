<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\DefaultStoreDefaultLocaleNotFoundException;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;

class LocaleDataProvider implements LocaleDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        Store $store
    ) {
        $this->storeFacade = $storeFacade;
        $this->localeFacade = $localeFacade;
        $this->store = $store;
    }

    /**
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\DefaultStoreDefaultLocaleNotFoundException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getDefaultStoreDefaultLocale(): LocaleTransfer
    {
        $defaultStore = $this->store::getDefaultStore();
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            if ($storeTransfer->getName() === $defaultStore) {
                $availableLocaleIsoCodes = array_values($storeTransfer->getAvailableLocaleIsoCodes());

                if (!isset($availableLocaleIsoCodes[0])) {
                    throw new DefaultStoreDefaultLocaleNotFoundException();
                }

                return $this->localeFacade->getLocale($availableLocaleIsoCodes[0]);
            }
        }

        throw new DefaultStoreDefaultLocaleNotFoundException();
    }
}
