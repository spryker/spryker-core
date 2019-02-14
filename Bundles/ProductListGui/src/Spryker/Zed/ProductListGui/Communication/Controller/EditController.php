<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class EditController extends ProductListAbstractController
{
    public const MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS = 'Product List "%s" has been successfully updated.';

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
            $this->addSuccessMessage(static::MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS, [
                '%s' => $productListTransfer->getTitle(),
            ]);
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
        $idProductList = $this->castId($request->get(static::URL_PARAM_ID_PRODUCT_LIST));
        $data = $this->prepareTemplateVariables($productListAggregateForm);
        $data['idProductList'] = $idProductList;

        return $data;
    }
}
