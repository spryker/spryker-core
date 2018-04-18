<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model;

use Generated\Shared\Transfer\SpyProductQuantityEntityTransfer;
use Spryker\Zed\ProductQuantity\Persistence\ProductQuantityRepositoryInterface;

class ProductQuantityReader implements ProductQuantityReaderInterface
{
    protected const DEFAULT_INTERVAL = 1;

    /**
     * @var \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityRepositoryInterface
     */
    protected $productQuantityRepository;

    /**
     * @param \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityRepositoryInterface $productQuantityRepository
     */
    public function __construct(ProductQuantityRepositoryInterface $productQuantityRepository)
    {
        $this->productQuantityRepository = $productQuantityRepository;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    public function findProductQuantityEntitiesByProductIds(array $productIds): array
    {
        $productQuantityEntities = $this->productQuantityRepository->findProductQuantityEntitiesByProductIds($productIds);

        foreach ($productQuantityEntities as $productQuantityEntity) {
            $this->filterProductQuantityEntity($productQuantityEntity);
        }

        return $productQuantityEntities;
    }

    /**
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    public function findProductQuantityEntitiesByProductSku(array $productSkus): array
    {
        $productQuantityEntities = $this->productQuantityRepository->findProductQuantityEntitiesByProductSku($productSkus);

        foreach ($productQuantityEntities as $productQuantityEntity) {
            $this->filterProductQuantityEntity($productQuantityEntity);
        }

        return $productQuantityEntities;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer $productQuantityEntity
     *
     * @return void
     */
    protected function filterProductQuantityEntity(SpyProductQuantityEntityTransfer $productQuantityEntity): void
    {
        if ($productQuantityEntity->getQuantityInterval() === null) {
            $productQuantityEntity->setQuantityInterval(static::DEFAULT_INTERVAL);
        }

        if ($productQuantityEntity->getQuantityMin() === null) {
            $productQuantityEntity->setQuantityMin($productQuantityEntity->getQuantityInterval());
        }
    }
}
