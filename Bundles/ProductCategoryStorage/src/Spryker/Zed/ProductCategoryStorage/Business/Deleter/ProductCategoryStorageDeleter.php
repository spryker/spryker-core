<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Deleter;

use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface;

class ProductCategoryStorageDeleter implements ProductCategoryStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface
     */
    protected $productCategoryStorageRepository;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface
     */
    protected $productCategoryStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface
     */
    protected $productAbstractReader;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface $productCategoryStorageEntityManager
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface $productAbstractReader
     */
    public function __construct(
        ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository,
        ProductCategoryStorageEntityManagerInterface $productCategoryStorageEntityManager,
        ProductAbstractReaderInterface $productAbstractReader
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productCategoryStorageRepository = $productCategoryStorageRepository;
        $this->productCategoryStorageEntityManager = $productCategoryStorageEntityManager;
        $this->productAbstractReader = $productAbstractReader;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCollectionByCategoryStoreIdEvents(array $eventEntityTransfers): void
    {
        $categoryStoreIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);
        $productAbstractIds = $this->productAbstractReader->getProductAbstractIdsByCategoryStoreIds($categoryStoreIds);

        $this->unpublish($productAbstractIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void
    {
        $productAbstractCategoryStorageKeys = $this->productCategoryStorageRepository
            ->getProductAbstractCategoryStorageKeysByProductAbstractIds($productAbstractIds);

        $this->productCategoryStorageEntityManager->deleteProductAbstractCategoryStorages($productAbstractCategoryStorageKeys);
    }
}
