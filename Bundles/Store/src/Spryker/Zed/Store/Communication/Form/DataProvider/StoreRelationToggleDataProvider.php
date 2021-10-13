<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Form\DataProvider;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class StoreRelationToggleDataProvider implements StoreRelationToggleDataProviderInterface
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
    public function getDefaultFormData()
    {
        return (new StoreRelationTransfer())
            ->setIdStores($this->getAllIdStores());
    }

    /**
     * @return array<int>
     */
    protected function getAllIdStores()
    {
        $storeTransferCollection = $this->storeFacade->getAllStores();

        $idStores = [];
        foreach ($storeTransferCollection as $storeTransfer) {
            $idStores[] = $storeTransfer->getIdStore();
        }

        return $idStores;
    }
}
