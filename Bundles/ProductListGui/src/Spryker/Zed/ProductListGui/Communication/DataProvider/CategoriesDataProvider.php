<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\DataProvider;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface;
use Spryker\Zed\ProductListGui\Communication\Form\CategoriesType;

class CategoriesDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface
     */
    protected $facade;

    /**
     * @param \Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface $facade
     */
    public function __construct(
        ProductListGuiFacadeInterface $facade
    ) {
        $this->facade = $facade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CategoriesType::OPTION_CATEGORY_ARRAY => $this->getCategoryList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getData(ProductListTransfer $productListTransfer)
    {
        $categoryIds = [];

//        if ($cmsBlockTransfer->getIdCmsBlock()) {
//            $categoryIds = $this->getAssignedCategoryIds($cmsBlockTransfer->getIdCmsBlock());
//        }
//
//        $productListTransfer->setIdCategories($categoryIds);

        return $productListTransfer;
    }

//    /**
//     * @param int $idCmsBlock
//     *
//     * @return array
//     */
//    protected function getAssignedCategoryIds($idCmsBlock)
//    {
//        $query = $this->cmsBlockCategoryConnectorQueryContainer
//            ->queryCmsBlockCategoryConnectorByIdCmsBlock($idCmsBlock)
//            ->find();
//
//        $assignedIdCategories = [];
//
//        foreach ($query as $item) {
//            $assignedIdCategories[$item->getFkCmsBlockCategoryPosition()][] = $item->getFkCategory();
//            $this->assertCmsBlockTemplate($item);
//        }
//
//        return $assignedIdCategories;
//    }

    /**
     * @return string[] [<category id> => <category name in english locale>]
     */
    protected function getCategoryList(): array
    {
        return array_flip($this->facade->getAllCategoriesNames());
    }
}
