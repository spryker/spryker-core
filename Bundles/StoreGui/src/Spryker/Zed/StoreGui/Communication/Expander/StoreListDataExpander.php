<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Expander;

use Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class StoreListDataExpander implements StoreListDataExpanderInterface
{
    /**
     * @var string
     */
    protected const QUERY_PARAMETER_STORE = '_store';

    /**
     * @var string
     */
    protected const HIDE_CATEGORY_FILTERS = 'hideCategoryFilters';

    /**
     * @var \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    protected $request;

    /**
     * @param \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface $storeFacade
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     */
    public function __construct(
        StoreGuiToStoreFacadeInterface $storeFacade,
        ?Request $request
    ) {
        $this->storeFacade = $storeFacade;
        $this->request = $request;
    }

    /**
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expandData(array $viewData): array
    {
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $viewData;
        }

        $storeTransfers = $this->storeFacade->getAllStores();
        $viewData['stores'] = $this->getStoresIndexedByStoreName($storeTransfers);
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->request;

        $viewData[static::HIDE_CATEGORY_FILTERS] = !$request->query->get(static::QUERY_PARAMETER_STORE);

        return $viewData;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getStoresIndexedByStoreName(array $storeTransfers): array
    {
        $stores = [];
        foreach ($storeTransfers as $storeTransfer) {
            $stores[$storeTransfer->getNameOrFail()] = $storeTransfer;
        }

        return $stores;
    }
}
