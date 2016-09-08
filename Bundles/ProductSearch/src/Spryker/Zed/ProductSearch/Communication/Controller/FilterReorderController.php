<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Controller;

use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductSearch\Communication\ProductSearchCommunicationFactory getFactory()
 */
class FilterReorderController extends AbstractController
{

    const PARAM_FILTER_LIST = 'filter_list';

    /**
     * @return array
     */
    public function indexAction()
    {
        $productSearchAttributes = $this->getFacade()->getProductSearchAttributeList();

        return $this->viewResponse([
            'productSearchAttributes' => $productSearchAttributes,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveAction(Request $request)
    {
        $filterList = $request->request
            ->get(self::PARAM_FILTER_LIST);

        if (!$filterList) {
            return $this->jsonResponse();
        }

        $productSearchAttributeList = $this->createProductSearchAttributeList($filterList);

        $this->getFacade()->updateProductSearchAttributeOrder($productSearchAttributeList);
        $this->getFacade()->touchProductSearchConfig();

        return $this->jsonResponse();
    }

    /**
     * @param array $filterList
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer[]
     */
    protected function createProductSearchAttributeList(array $filterList)
    {
        $productSearchAttributeList = [];

        $position = 1;
        foreach ($filterList as $filter) {
            $productSearchAttributeTransfer = new ProductSearchAttributeTransfer();
            $productSearchAttributeTransfer
                ->setIdProductSearchAttribute($filter[ProductSearchAttributeTransfer::ID_PRODUCT_SEARCH_ATTRIBUTE])
                ->setPosition($position++);

            $productSearchAttributeList[] = $productSearchAttributeTransfer;
        }

        return $productSearchAttributeList;
    }

}
