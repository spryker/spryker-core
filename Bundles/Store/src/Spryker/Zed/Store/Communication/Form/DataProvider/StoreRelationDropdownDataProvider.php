<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Form\DataProvider;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class StoreRelationDropdownDataProvider implements StoreRelationDropdownDataProviderInterface
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
    public function getDefaultFormData(): StoreRelationTransfer
    {
        return (new StoreRelationTransfer())
            ->setIdStores($this->getAllIdStores());
    }

    /**
     * @return int[]
     */
    protected function getAllIdStores(): array
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $this->storeFacade->getAllStores());
    }
}
