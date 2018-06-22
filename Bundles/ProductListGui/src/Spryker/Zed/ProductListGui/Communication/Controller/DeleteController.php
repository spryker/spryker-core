<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    public const PARAM_ID_PRODUCT_LIST = 'id-product-list';

    protected const PARAM_REDIRECT_URL = 'redirect-url';
    protected const URL_LIST = '/product-list-gui';
    protected const MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS = 'Product list with id "%d" successfully deleted';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idProductList = $request->query->get(static::PARAM_ID_PRODUCT_LIST);
        $idProductList = $this->castId($idProductList);
        $productListTransfer = (new ProductListTransfer())
            ->setIdProductList($idProductList);

        $this->getFactory()
            ->getProductListFacade()
            ->deleteProductList($productListTransfer);

        //TODO find out about error response

        $this->addSuccessMessage(sprintf(
            static::MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS,
            $idProductList
        ));
        $redirectUrl = $request->query->get(static::PARAM_REDIRECT_URL, static::URL_LIST);

        return new RedirectResponse($redirectUrl);
    }
}
