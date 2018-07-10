<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Spryker\Shared\ProductListGui\ProductListGuiConstants;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class EditController extends ProductListAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $productListAggregateForm = $this->createProductListAggregateForm($request);
        $productListTransfer = $this->handleProductListAggregateForm(
            $request,
            $productListAggregateForm
        );

        if ($productListTransfer) {
            $this->addSuccessMessage(sprintf(
                ProductListGuiConstants::MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS,
                $productListTransfer->getTitle()
            ));

            return $this->redirectResponse(ProductListGuiConstants::REDIRECT_URL_DEFAULT);
        }

        return $this->viewResponse($this->executeEditAction($request, $productListAggregateForm));
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $productListAggregateForm
     *
     * @return array
     */
    protected function executeEditAction(Request $request, FormInterface $productListAggregateForm)
    {
        $idProductList = $this->castId($request->get(ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST));
        $data = $this->executeCreateAction($productListAggregateForm);
        $data['idProductList'] = $idProductList;

        return $data;
    }
}
