<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;

class LocaleDataProvider implements LocaleDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade, Store $store)
    {
        $this->storeFacade = $storeFacade;
        $this->store = $store;
    }

    /**
     * @return string|null
     */
    public function findDefaultStoreDefaultLocale(): ?string
    {
        $defaultStore = $this->store::getDefaultStore();
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            if ($storeTransfer->getName() === $defaultStore) {
                return array_values($storeTransfer->getAvailableLocaleIsoCodes())[0] ?? null;
            }
        }

        return null;
    }
}
