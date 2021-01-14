<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Loader;

use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface;

class CategoryTreeLoader implements CategoryTreeLoaderInterface
{
    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_ID_CATEGORY_NODE
     */
    protected const COL_ID_CATEGORY_NODE = 'id_category_node';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_FK_CATEGORY_NODE_DESCENDANT
     */
    protected const COL_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface
     */
    protected $productCategoryStorageRepository;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
     */
    public function __construct(ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository)
    {
        $this->productCategoryStorageRepository = $productCategoryStorageRepository;
    }

    /**
     * @return array
     */
    public function loadCategoryTree(): array
    {
        $categoryTree = [];

        $categories = $this->productCategoryStorageRepository->getAllCategoriesOrderedByDescendant();
        $formattedCategories = $this->formatCategories($categories);

        $categoryNodeIds = $this->productCategoryStorageRepository->getAllCategoryNodeIds();

        foreach ($categoryNodeIds as $idCategoryNode) {
            $pathData = [];

            if (isset($formattedCategories[$idCategoryNode])) {
                $pathData = $formattedCategories[$idCategoryNode];
            }

            $categoryTree[$idCategoryNode] = [];

            foreach ($pathData as $path) {
                if (!in_array((int)$path[static::COL_ID_CATEGORY_NODE], $categoryTree[$idCategoryNode])) {
                    $categoryTree[$idCategoryNode][] = $path;
                }
            }
        }

        return $categoryTree;
    }

    /**
     * @param array $categories
     *
     * @return array
     */
    protected function formatCategories(array $categories): array
    {
        $formattedCategories = [];

        foreach ($categories as $category) {
            $formattedCategories[$category[static::COL_FK_CATEGORY_NODE_DESCENDANT]][] = $category;
        }

        return $formattedCategories;
    }
}
