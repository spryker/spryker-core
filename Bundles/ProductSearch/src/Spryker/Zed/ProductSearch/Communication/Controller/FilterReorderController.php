<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductSearch\Communication\ProductSearchCommunicationFactory getFactory()
 */
class FilterReorderController extends AbstractController
{
    public const PARAM_FILTER_LIST = 'filter_list';

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

        $sortedProductSearchAttributeList = $this
            ->getFactory()
            ->createSortedProductSearchTransferListMapper()
            ->createList($filterList);

        $this->getFacade()->updateProductSearchAttributeOrder($sortedProductSearchAttributeList);
        $this->getFacade()->touchProductSearchConfigExtension();

        return $this->jsonResponse();
    }
}
