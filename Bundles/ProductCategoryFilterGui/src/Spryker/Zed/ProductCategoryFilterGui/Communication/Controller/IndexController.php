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
     * @return array
     */
    public function indexAction()
    {
        $rootCategoriesTable = $this
            ->getFactory()
            ->createCategoryRootNodeTable($this->getCurrentLocale()->getIdLocale());
        return $this->viewResponse([
            'RootCategoriesTable' => $rootCategoriesTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productTable = $this
            ->getFactory()
            ->createCategoryRootNodeTable($this->getCurrentLocale()->getIdLocale());

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }

    /**
     * @return void
     */
    public function testingAction()
    {
        $categoryId = 10;
        $this->getFactory()->getProductCategoryFilterFacade()->createProductCategoryFilter(
            (new ProductCategoryFilterTransfer())->fromArray(
                [
                    ProductCategoryFilterTransfer::FK_CATEGORY => $categoryId,
                    ProductCategoryFilterTransfer::FILTER_DATA => json_encode([
                        'weight' => true,
                        'price' => false,
                        'category' => true,
                    ]),
                ],
                true
            )
        );
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();
    }
}
