<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\StoreGui\Communication\Form\Type\StoreRelationDropdownType;
use Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface;

class StoreRelationDropdownDataProvider
{
    /**
     * @var \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(StoreGuiToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getData(): StoreRelationTransfer
    {
        $allStoreIds = array_keys($this->getStoreNamesIndexedByIdStore());

        return (new StoreRelationTransfer())
            ->setIdStores($allStoreIds);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            StoreRelationDropdownType::OPTION_DATA_CLASS => StoreRelationTransfer::class,
            StoreRelationDropdownType::OPTION_INACTIVE_CHOICES => [],
            StoreRelationDropdownType::OPTION_ATTRIBUTE_ACTION_URL => '',
            StoreRelationDropdownType::OPTION_ATTRIBUTE_ACTION_EVENT => '',
            StoreRelationDropdownType::OPTION_ATTRIBUTE_ACTION_FIELD => '',
            StoreRelationDropdownType::OPTION_STORE_CHOICES => $this->getStoreNamesIndexedByIdStore(),
            StoreRelationDropdownType::OPTION_EXTENDED => true,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getStoreNamesIndexedByIdStore(): array
    {
        $storeTransferCollection = $this->storeFacade->getStoresAvailableForCurrentPersistence();

        $storeNameMap = [];
        foreach ($storeTransferCollection as $storeTransfer) {
            $storeNameMap[$storeTransfer->getIdStoreOrFail()] = $storeTransfer->getNameOrFail();
        }

        return $storeNameMap;
    }
}
