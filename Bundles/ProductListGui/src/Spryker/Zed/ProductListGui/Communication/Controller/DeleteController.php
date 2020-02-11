<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Generated\Shared\Transfer\ProductListTransfer;
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
        $idProductList = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_LIST));

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
        $defaultRedirectUrl = $this->getFactory()
            ->getConfig()
            ->getDefaultRedirectUrl();

        $redirectUrl = $request->query->get(
            static::URL_PARAM_REDIRECT_URL,
            $defaultRedirectUrl
        );

        $idProductList = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_LIST));
        $productListTransfer = (new ProductListTransfer())->setIdProductList($idProductList);

        $productListResponseTransfer = $this->getFactory()
            ->getProductListFacade()
            ->removeProductList($productListTransfer);

        $this->addMessagesFromProductListResponseTransfer($productListResponseTransfer);

        return $this->redirectResponse($redirectUrl);
    }
}
