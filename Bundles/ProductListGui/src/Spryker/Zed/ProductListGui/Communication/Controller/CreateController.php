<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    /**
     * @see \Spryker\Zed\ProductListGui\Communication\Controller\IndexController::indexAction()
     */
    protected const URL_LIST = '/product-list-gui';
    protected const MESSAGE_PRODUCT_LIST_CREATE_ERROR = 'Product list can not be created';
    protected const MESSAGE_PRODUCT_LIST_CREATE_SUCCESS = 'Product list with id "%d" successfully created';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse twig variables
     */
    public function indexAction(Request $request)
    {
        $redirectUrl = $request->query->get(static::PARAM_REDIRECT_URL, static::URL_LIST);
        $productListTransfer = new ProductListTransfer();
        $form = $this->getFactory()
            ->getProductListForm($productListTransfer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productListTransfer = $form->getData();
            $productListTransfer = $this->getFactory()
                ->getProductListFacade()
                ->saveProductList($productListTransfer);

            if ($productListTransfer->getIdProductList()) {
                $this->addSuccessMessage(sprintf(
                    static::MESSAGE_PRODUCT_LIST_CREATE_SUCCESS,
                    $productListTransfer->getIdProductList()
                ));

                return $this->redirectResponse($redirectUrl);
            }

            $this->addErrorMessage(static::MESSAGE_PRODUCT_LIST_CREATE_ERROR);
        }

        $tabs = $this->getFactory()->createProductListTabs();
        $productConcreteTable = $this->getFactory()->createProductConcreteTable();
        $productConcreteTabs = $this->getFactory()->createProductConcreteTabs();

        return $this->viewResponse([
            'form' => $form->createView(),
            'productListFormTabs' => $tabs->createView(),
            'productConcreteFormTabs' => $productConcreteTabs->createView(),
            'productTable' => $productConcreteTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $productConcreteTable = $this->getFactory()->createProductConcreteTable();

        return $this->jsonResponse(
            $productConcreteTable->fetchData()
        );
    }
}
