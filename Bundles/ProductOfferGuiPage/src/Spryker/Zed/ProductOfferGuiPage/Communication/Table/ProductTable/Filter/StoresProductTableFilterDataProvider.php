<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter;

use Generated\Shared\Transfer\TableFilterTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface;

class StoresProductTableFilterDataProvider implements ProductTableFilterDataProviderInterface
{
    public const FILTER_NAME = 'store';

    protected const KEY_KEY = 'key';
    protected const KEY_VALUE = 'value';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface $storeFacade
     */
    public function __construct(ProductOfferGuiPageToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\TableFilterTransfer
     */
    public function getFilterData(): TableFilterTransfer
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $indexedStoreNames = $this->getStoreNamesIndexedByStoreIds($storeTransfers);

        return (new TableFilterTransfer())
            ->setKey(static::FILTER_NAME)
            ->setTitle('Stores')
            ->setType('select')
            ->addOption(static::OPTION_NAME_MULTISELECT, true)
            ->addOption(static::OPTION_NAME_VALUES, $indexedStoreNames);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return array
     */
    protected function getStoreNamesIndexedByStoreIds(array $storeTransfers): array
    {
        $indexedStoreNames = [];

        foreach ($storeTransfers as $storeTransfer) {
            $indexedStoreNames[] = [
                static::OPTION_VALUE_KEY_TITLE => $storeTransfer->getName(),
                static::OPTION_VALUE_KEY_VALUE => $storeTransfer->getIdStore(),
            ];
        }

        return $indexedStoreNames;
    }
}
