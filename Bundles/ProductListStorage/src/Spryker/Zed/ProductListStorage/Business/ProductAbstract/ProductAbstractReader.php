<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductAbstract;

use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToLocaleFacadeInterface;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductCategoryFacadeInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;

class ProductAbstractReader implements ProductAbstractReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface
     */
    protected $productListStorageRepository;

    /**
     * @var \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductCategoryFacadeInterface
     */
    protected $productCategoryFacade;

    /**
     * @var \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductListStorageRepositoryInterface $productListStorageRepository,
        ProductListStorageToProductCategoryFacadeInterface $categoryFacade,
        ProductListStorageToLocaleFacadeInterface $localeFacade
    ) {
        $this->productListStorageRepository = $productListStorageRepository;
        $this->productCategoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->productListStorageRepository->findProductAbstractIdsByProductConcreteIds($productConcreteIds);
    }

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        $productAbstractIds = [];
        $currentLocale = $this->localeFacade->getCurrentLocale();
        foreach ($categoryIds as $categoryId) {
            $productAbstractTransfers = $this->productCategoryFacade->getAbstractProductsByIdCategory(
                $categoryId,
                $currentLocale
            );

            foreach ($productAbstractTransfers as $productAbstractTransfer) {
                $productAbstractIds[] = $productAbstractTransfer->getIdProductAbstract();
            }
        }

        return array_unique($productAbstractIds);
    }
}
