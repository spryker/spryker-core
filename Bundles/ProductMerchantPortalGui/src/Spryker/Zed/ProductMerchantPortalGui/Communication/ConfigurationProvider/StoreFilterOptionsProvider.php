<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider;

use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;

class StoreFilterOptionsProvider implements StoreFilterOptionsProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @phpstan-return array<int, string>
     *
     * @return string[]
     */
    public function getStoreOptions(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $storeOptions = [];
        foreach ($storeTransfers as $storeTransfer) {
            if ($storeTransfer->getName() === null) {
                continue;
            }
            $storeOptions[(int)$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $storeOptions;
    }
}
