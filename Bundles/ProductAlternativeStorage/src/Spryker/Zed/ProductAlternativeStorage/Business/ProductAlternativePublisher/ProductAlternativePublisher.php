<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
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
    ) {
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
            $sku = $this->productAlternativeStorageRepository->findProductSkuById($productId);

            $productAlternativeStorageEntity = $this->findProductAlternativeStorageEntity($productId);

            if (!$productAlternativeStorageEntity) {
                $productAlternativeStorageEntity = new SpyProductAlternativeStorageEntityTransfer();
            }

            $this->saveStorageEntity($productAlternativeStorageEntity, $abstractAlternatives, $concreteAlternatives, $sku, $productId);
        }
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer|null
     */
    protected function findProductAlternativeStorageEntity($idProduct): ?SpyProductAlternativeStorageEntityTransfer
    {
        return $this->productAlternativeStorageRepository->findProductAlternativeStorageEntity($idProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntity
     * @param string[] $alternativeAbstractIds
     * @param string[] $alternativeConcreteIds
     * @param string $sku
     * @param int $productId
     *
     * @return void
     */
    protected function saveStorageEntity(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntity,
        $alternativeAbstractIds,
        $alternativeConcreteIds,
        $sku,
        $productId
    ): void {
        $productAlternativeStorageEntity
            ->setFkProduct($productId)
            ->setSku($sku)
            ->setData($this->getStorageEntityData($alternativeAbstractIds, $alternativeConcreteIds));

        $this->productAlternativeStorageEntityManager->saveProductAlternativeStorageEntity($productAlternativeStorageEntity);
    }

    /**
     * @param string[] $alternativeAbstractIds
     * @param string[] $alternativeConcreteIds
     *
     * @return array
     */
    protected function getStorageEntityData(
        $alternativeAbstractIds,
        $alternativeConcreteIds
    ): array {
        return (new ProductAlternativeStorageTransfer())
            ->setProductAbstractIds($alternativeAbstractIds)
            ->setProductConcreteIds($alternativeConcreteIds)
            ->toArray();
    }
}
