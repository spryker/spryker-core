<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeUnpublisher;

use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface;

class ProductAlternativeUnpublisher implements ProductAlternativeUnpublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface
     */
    protected $alternativeStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface
     */
    protected $productAlternativeStorageRepository;

    /**
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface $alternativeStorageEntityManager
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository
     */
    public function __construct(
        ProductAlternativeStorageEntityManagerInterface $alternativeStorageEntityManager,
        ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository
    ) {
        $this->alternativeStorageEntityManager = $alternativeStorageEntityManager;
        $this->productAlternativeStorageRepository = $productAlternativeStorageRepository;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function unpublish(array $productIds): void
    {
        foreach ($productIds as $productId) {
            $this->alternativeStorageEntityManager->deleteProductAlternativeStorageEntity(
                $this->findProductAlternativeStorageEntity($productId)
            );
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
}
