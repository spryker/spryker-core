<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class EditController extends ProductListAbstractController
{
    public const MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS = 'Product List "%s" has been successfully updated.';

    protected const ROUTE_REDIRTECT = '/product-list-gui/edit';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $productListAggregateForm = $this->createProductListAggregateForm($request);
        $productListTransfer = $this->findProductListTransfer(
            $request,
            $productListAggregateForm
        );

        if ($productListTransfer === null) {
            $productListTransfer = (new ProductListTransfer())->setIdProductList(
                $this->castId($request->get(static::URL_PARAM_ID_PRODUCT_LIST))
            );

            return $this->viewResponse($this->executeEditAction($productListTransfer, $productListAggregateForm));
        }

        $productListResponseTransfer = $this->getFactory()
            ->getProductListFacade()
            ->updateProductList($productListTransfer);

        $this->addMessagesFromProductListResponseTransfer($productListResponseTransfer);

        if ($productListResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS, [
                '%s' => $productListTransfer->getTitle(),
            ]);
        }

        $redirectUrl = Url::generate(
            static::ROUTE_REDIRTECT,
            [static::URL_PARAM_ID_PRODUCT_LIST => $productListTransfer->getIdProductList()]
        )->build();

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableProductConcreteTableAction(): JsonResponse
    {
        $availableProductConcreteTable = $this->getFactory()->createAvailableProductConcreteTable();

        return $this->jsonResponse(
            $availableProductConcreteTable->fetchData()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedProductConcreteTableAction(): JsonResponse
    {
        $assignedProductConcreteTable = $this->getFactory()->createAssignedProductConcreteTable();

        return $this->jsonResponse(
            $assignedProductConcreteTable->fetchData()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Symfony\Component\Form\FormInterface $productListAggregateForm
     *
     * @return array
     */
    protected function executeEditAction(ProductListTransfer $productListTransfer, FormInterface $productListAggregateForm)
    {
        $data = $this->prepareTemplateVariables($productListAggregateForm);
        $data['idProductList'] = $productListTransfer->getIdProductList();

        $data['productListUsedByTableData'] = $this->getFactory()
            ->createProductListUsedByTableDataProvider()
            ->getTableData($productListTransfer);

        $data['productListAggregationTabs'] = $this->getFactory()
            ->createProductListEditAggregationTabs()
            ->createView();

        return $data;
    }
}
