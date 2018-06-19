<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher;


use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Spryker\Zed\ProductAlternativeStorage\Dependency\Facade\ProductAlternativeStorageToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface;

class ProductAlternativePublisher implements ProductAlternativePublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Dependency\Facade\ProductAlternativeStorageToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface
     */
    protected $productAlternativeStorageRepository;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface
     */
    protected $productAlternativeStorageEntityManager;


    /**
     * @param \Spryker\Zed\ProductAlternativeStorage\Dependency\Facade\ProductAlternativeStorageToProductAlternativeFacadeInterface $productAlternativeFacade
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface $productAlternativeStorageEntityManager
     */
    public function __construct(
        ProductAlternativeStorageToProductAlternativeFacadeInterface $productAlternativeFacade,
        ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository,
        ProductAlternativeStorageEntityManagerInterface $productAlternativeStorageEntityManager
    )
    {
        $this->productAlternativeFacade = $productAlternativeFacade;
        $this->productAlternativeStorageRepository = $productAlternativeStorageRepository;
        $this->productAlternativeStorageEntityManager = $productAlternativeStorageEntityManager;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void
    {
        foreach ($productIds as $productId) {
            $abstractAlternatives = $this->productAlternativeStorageRepository->findAbstractAlternativesIdsByConcreteProductId($productId);
            $concreteAlternatives = $this->productAlternativeStorageRepository->findConcreteAlternativesIdsByConcreteProductId($productId);

            $productAlternativeTransfer = $this->productAlternativeFacade->findProductAlternativeTransfer($productId);
            $productAlternativeStorageEntity = $this->findProductAlternativeStorageEntity($productId);

            $this->saveStorageEntity($productAlternativeStorageEntity, $productAlternativeTransfer, $abstractAlternatives, $concreteAlternatives);
        }
    }

    /**
     * @param int $IdProduct
     *
     * @return SpyProductAlternativeStorageEntityTransfer
     */
    protected function findProductAlternativeStorageEntity($IdProduct): ProductAlternativeTransfer
    {
        return $this->productAlternativeStorageRepository->findProductAlternativeStorageEntity($IdProduct);
    }

    /**
     * @param SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntity
     *
     * @param ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @param $alternativeAbstractIds
     *
     * @param $alternativeConcreteIds
     *
     * @return void
     */

    protected function saveStorageEntity(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntity,
        ProductAlternativeTransfer $productAlternativeTransfer,
        $alternativeAbstractIds,
        $alternativeConcreteIds
    ): void
    {
        $productAlternativeStorageEntity
            ->setSku($this->productAlternativeStorageRepository->findProductSkuById($productAlternativeTransfer->getIdProduct()))
            ->setData($this->getStorageEntityData($productAlternativeTransfer, $alternativeAbstractIds, $alternativeConcreteIds));

        $this->productAlternativeStorageEntityManager->saveProductAlternativeStorageEntity($productAlternativeStorageEntity);
    }

    /**
     * @param ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @param $alternativeAbstractIds
     *
     * @param $alternativeConcreteIds
     *
     * @return array
     */
    protected function getStorageEntityData(
        ProductAlternativeTransfer $productAlternativeTransfer,
        $alternativeAbstractIds,
        $alternativeConcreteIds
    ): array
    {
        return (new ProductAlternativeStorageTransfer())
            ->fromArray($productAlternativeTransfer->toArray(), true)
            ->setProductAbstractIds($alternativeAbstractIds)
            ->setProductConcreteIds($alternativeConcreteIds)
            ->toArray();
    }
}
