<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedPublisher;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface;

class ProductDiscontinuedPublisher implements ProductDiscontinuedPublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface
     */
    protected $discontinuedStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface
     */
    protected $productDiscontinuedStorageRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface $discontinuedStorageEntityManager
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     */
    public function __construct(
        ProductDiscontinuedStorageEntityManagerInterface $discontinuedStorageEntityManager,
        ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository,
        ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
    ) {
        $this->discontinuedStorageEntityManager = $discontinuedStorageEntityManager;
        $this->productDiscontinuedStorageRepository = $productDiscontinuedStorageRepository;
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return void
     */
    public function publish(array $productDiscontinuedIds): void
    {
        $productDiscontinuedCollectionTransfer = $this->findProductDiscontinuedCollection($productDiscontinuedIds);
        $productDiscontinuedStorageEntityTransfers = $this->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);

        $this->storeData($productDiscontinuedCollectionTransfer, $productDiscontinuedStorageEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer[] $productDiscontinuedStorageEntityTransfers
     *
     * @return void
     */
    protected function storeData(
        ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer,
        array $productDiscontinuedStorageEntityTransfers
    ): void {
        $indexProductDiscontinuedStorageEntityTransfers = $this->indexProductDiscontinuedStorageEntities($productDiscontinuedStorageEntityTransfers);
        foreach ($productDiscontinuedCollectionTransfer->getProductDiscontinueds() as $productDiscontinuedTransfer) {
            if (isset($indexProductDiscontinuedStorageEntityTransfers[$productDiscontinuedTransfer->getIdProductDiscontinued()])) {
                $this->storeDataSet($productDiscontinuedTransfer, $indexProductDiscontinuedStorageEntityTransfers[$productDiscontinuedTransfer->getIdProductDiscontinued()]);

                continue;
            }

            $this->storeDataSet($productDiscontinuedTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer|null $productDiscontinuedStorageEntityTransfer
     *
     * @return void
     */
    protected function storeDataSet(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        ?SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer = null
    ): void {
        if ($productDiscontinuedStorageEntityTransfer === null) {
            $productDiscontinuedStorageEntityTransfer = new SpyProductDiscontinuedStorageEntityTransfer();
        }

        $productDiscontinuedStorageEntityTransfer->setFkProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued())
            ->setSku($productDiscontinuedTransfer->getSku())
            ->setData(
                $this->mapStorageTransfer($productDiscontinuedTransfer)->toArray()
            );

        $this->discontinuedStorageEntityManager->saveProductDiscontinuedStorageEntity($productDiscontinuedStorageEntityTransfer);
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    protected function findProductDiscontinuedCollection(array $productDiscontinuedIds): ProductDiscontinuedCollectionTransfer
    {
        $criteriaFilterTransfer = (new ProductDiscontinuedCriteriaFilterTransfer)
            ->setIds($productDiscontinuedIds);

        return $this->productDiscontinuedFacade->findProductDiscontinuedCollection($criteriaFilterTransfer);
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer[]
     */
    protected function findProductDiscontinuedStorageEntitiesByIds(array $productDiscontinuedIds): array
    {
        return $this->productDiscontinuedStorageRepository->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer[] $productDiscontinuedStorageEntityTransfers
     *
     * @return \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer[]
     */
    protected function indexProductDiscontinuedStorageEntities(array $productDiscontinuedStorageEntityTransfers): array
    {
        $indexProductDiscontinuedStorageEntityTransfers = [];
        foreach ($productDiscontinuedStorageEntityTransfers as $discontinuedStorageEntityTransfer) {
            $indexProductDiscontinuedStorageEntityTransfers[$discontinuedStorageEntityTransfer->getFkProductDiscontinued()]
                = $discontinuedStorageEntityTransfer;
        }

        return $indexProductDiscontinuedStorageEntityTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer
     */
    protected function mapStorageTransfer(ProductDiscontinuedTransfer $productDiscontinuedTransfer): ProductDiscontinuedStorageTransfer
    {
        return (new ProductDiscontinuedStorageTransfer())
            ->fromArray($productDiscontinuedTransfer->toArray(), true);
    }
}
