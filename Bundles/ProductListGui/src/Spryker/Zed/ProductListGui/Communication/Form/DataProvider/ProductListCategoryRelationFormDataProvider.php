<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListAggregateFormType;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToCategoryFacadeInterface;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface;

class ProductListCategoryRelationFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @module ProductCategory
     *
     * @param \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductListGuiToProductListFacadeInterface $productListFacade,
        ProductListGuiToCategoryFacadeInterface $categoryFacade,
        ProductListGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->productListFacade = $productListFacade;
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListCategoryRelationTransfer
     */
    public function getData(?int $idProductList = null): ProductListCategoryRelationTransfer
    {
        $productListCategoryRelationTransfer = new ProductListCategoryRelationTransfer();

        if (!$idProductList) {
            return $productListCategoryRelationTransfer;
        }

        $productListTransfer = (new ProductListTransfer())->setIdProductList($idProductList);
        $productListCategoryRelation = $this->productListFacade
            ->getProductListById($productListTransfer)
            ->getProductListCategoryRelation();

        $productListCategoryRelation->setIdProductList($productListTransfer->getIdProductList());

        return $productListCategoryRelation;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $categoryCollectionTransfer = $this->categoryFacade->getAllCategoryCollection($this->localeFacade->getCurrentLocale());
        $categoryOptions = [];

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            foreach ($categoryTransfer->getNodeCollection()->getNodes() as $nodeTransfer) {
                $path = $nodeTransfer->getIsRoot()
                    ? $categoryTransfer->getName()
                    : sprintf('%s/%s', $nodeTransfer->getPath(), $categoryTransfer->getName());
                $categoryOptions[$path] = $categoryTransfer->getIdCategory();
            }
        }

        return [
            ProductListAggregateFormType::OPTION_CATEGORY_IDS => array_flip($categoryOptions),
        ];
    }
}
