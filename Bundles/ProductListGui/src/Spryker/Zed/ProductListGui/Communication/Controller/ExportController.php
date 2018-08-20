<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ExportController extends AbstractController
{
    public const PARAM_ID_PRODUCT_LIST = 'id-product-list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $response = $this->getFactory()->createProductListExporter()->exportToCsvFile(
            $this->getProductList($request)
        );

        return $response->send();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function getProductList(Request $request): ProductListTransfer
    {
        $idProductList = $request->query->get(static::PARAM_ID_PRODUCT_LIST);
        $idProductList = $this->castId($idProductList);
        $productListTransfer = (new ProductListTransfer())
            ->setIdProductList($idProductList);

        return $this->getFactory()
            ->getProductListFacade()
            ->getProductListById($productListTransfer);
    }
}
