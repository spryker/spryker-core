<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Reader;

use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface;

class ProductAbstractReader implements ProductAbstractReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface
     */
    protected $productCategoryStorageRepository;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface $categoryFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
     */
    public function __construct(
        ProductCategoryStorageToCategoryInterface $categoryFacade,
        ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->productCategoryStorageRepository = $productCategoryStorageRepository;
    }

    /**
     * @param int[] $categoryStoreIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryStoreIds(array $categoryStoreIds): array
    {
        $categoryIds = $this->productCategoryStorageRepository->getCategoryIdsByCategoryStoreIds($categoryStoreIds);
        $relatedCategoryIds = $this->getRelatedCategoryIds($categoryIds);

        return $this->productCategoryStorageRepository->getProductAbstractIdsByCategoryIds($relatedCategoryIds);
    }

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        $relatedCategoryIds = $this->getRelatedCategoryIds($categoryIds);

        return $this->productCategoryStorageRepository->getProductAbstractIdsByCategoryIds($relatedCategoryIds);
    }

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getRelatedCategoryIds(array $categoryIds): array
    {
        $relatedCategoryIds = [];

        foreach ($categoryIds as $idCategory) {
            $categoryNodes = $this->categoryFacade->getAllNodesByIdCategory($idCategory);

            foreach ($categoryNodes as $categoryNode) {
                $relatedCategoryIds[] = $this->productCategoryStorageRepository
                    ->getAllCategoryIdsByCategoryNodeId($categoryNode->getIdCategoryNode());
            }
        }

        $relatedCategoryIds = array_merge(...$relatedCategoryIds);

        return array_unique($relatedCategoryIds);
    }
}
