<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @return void
     */
    public function indexAction()
    {
        $categoryId = 10;
        $this->getFactory()->getProductCategoryFilterFacade()->deleteProductCategoryFilterByCategoryId($categoryId);
        $this->getFactory()->getProductCategoryFilterFacade()->createProductCategoryFilter(
            (new ProductCategoryFilterTransfer())->fromArray(
                [
                    ProductCategoryFilterTransfer::FK_CATEGORY => $categoryId,
                    ProductCategoryFilterTransfer::FILTER_DATA => '{attr:value}',
                ],
                true
            )
        );
    }
}
