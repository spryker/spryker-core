<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business\ProductAbstract;

use Spryker\Zed\ProductListSearch\Communication\Dependency\Facade\ProductListSearchToLocaleFacadeInterface;
use Spryker\Zed\ProductListSearch\Communication\Dependency\Facade\ProductListSearchToProductCategoryFacadeInterface;
use Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface;

class ProductAbstractReader implements ProductAbstractReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface
     */
    protected $productListSearchRepository;

    /**
     * @var \Spryker\Zed\ProductListSearch\Communication\Dependency\Facade\ProductListSearchToProductCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductListSearch\Communication\Dependency\Facade\ProductListSearchToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface $productListSearchRepository
     * @param \Spryker\Zed\ProductListSearch\Communication\Dependency\Facade\ProductListSearchToProductCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\ProductListSearch\Communication\Dependency\Facade\ProductListSearchToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductListSearchRepositoryInterface $productListSearchRepository,
        ProductListSearchToProductCategoryFacadeInterface $categoryFacade,
        ProductListSearchToLocaleFacadeInterface $localeFacade
    ) {
        $this->productListSearchRepository = $productListSearchRepository;
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByConcreteIds(array $productConcreteIds): array
    {
        return $this->productListSearchRepository->getProductAbstractIdsByConcreteIds($productConcreteIds);
    }

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        $productAbstractIds = [];
        $currentLocale = $this->localeFacade->getCurrentLocale();
        foreach ($categoryIds as $categoryId) {
            $productAbstractTransfers = $this->categoryFacade->getAbstractProductsByIdCategory(
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
