<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListAggregateFormType;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface;
use Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface;

class ProductListCategoryRelationFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @var \Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface
     */
    protected $productListGuiRepository;

    /**
     * @module ProductCategory
     *
     * @param \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface $productListGuiRepository
     */
    public function __construct(
        ProductListGuiToProductListFacadeInterface $productListFacade,
        ProductListGuiRepositoryInterface $productListGuiRepository
    ) {
        $this->productListFacade = $productListFacade;
        $this->productListGuiRepository = $productListGuiRepository;
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
        return [
            ProductListAggregateFormType::OPTION_CATEGORY_IDS => $this->productListGuiRepository->getCategoriesWithPaths(),
        ];
    }
}
