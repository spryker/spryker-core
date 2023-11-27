<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Controller;

use Generated\Shared\Transfer\ProductSearchAttributeConditionsTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductSearch\Communication\ProductSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchRepositoryInterface getRepository()
 */
class FilterReorderController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_FILTER_LIST = 'filter_list';

    /**
     * @return array
     */
    public function indexAction()
    {
        $sortTransfer = (new SortTransfer())
            ->setField(ProductSearchAttributeTransfer::POSITION)
            ->setIsAscending(true);
        $productSearchAttributeConditionsTransfer = (new ProductSearchAttributeConditionsTransfer())
            ->setWithLocalizedAttributes(true);
        $productSearchAttributeCriteriaTransfer = (new ProductSearchAttributeCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setProductSearchAttributeConditions($productSearchAttributeConditionsTransfer);

        $productSearchAttributeCollectionTransfer = $this->getFacade()->getProductSearchAttributeCollection($productSearchAttributeCriteriaTransfer);

        return $this->viewResponse([
            'productSearchAttributes' => $productSearchAttributeCollectionTransfer->getProductSearchAttributes()->getArrayCopy(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveAction(Request $request)
    {
        /** @var array $filterList */
        $filterList = $request->request
            ->all(static::PARAM_FILTER_LIST);

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
