<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Shared\ProductListGui\ProductListGuiConstants;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class DeleteController extends ProductListAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idProductList = $this->castId($request->query->get(ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST));

        return $this->viewResponse([
            'idProductList' => $idProductList,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request)
    {
        $redirectUrl = $request->query->get(
            ProductListGuiConstants::URL_PARAM_REDIRECT_URL,
            ProductListGuiConstants::REDIRECT_URL_DEFAULT
        );
        $idProductList = $this->castId($request->query->get(ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST));
        $productListTransfer = (new ProductListTransfer())->setIdProductList($idProductList);

        $this->getFactory()
            ->getProductListFacade()
            ->deleteProductList($productListTransfer);

        $this->addSuccessMessage(ProductListGuiConstants::MESSAGE_PRODUCT_LIST_DELETE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
