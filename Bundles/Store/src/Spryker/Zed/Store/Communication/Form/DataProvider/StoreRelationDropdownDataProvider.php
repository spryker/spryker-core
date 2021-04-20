<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Form\DataProvider;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Store\Communication\Form\Type\StoreRelationDropdownType;

class StoreRelationDropdownDataProvider
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(StoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getData(): StoreRelationTransfer
    {
        $allStoreIds = array_keys($this->getStoreNameMap());

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
            StoreRelationDropdownType::OPTION_STORE_CHOICES => $this->getStoreNameMap(),
        ];
    }

    /**
     * @return string[]
     */
    protected function getStoreNameMap(): array
    {
        $storeTransferCollection = $this->storeFacade->getAllStores();

        $storeNameMap = [];
        foreach ($storeTransferCollection as $storeTransfer) {
            $storeNameMap[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $storeNameMap;
    }
}
