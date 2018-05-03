<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductBarcodeGui\Communication\ProductBarcodeGuiCommunicationFactory getFactory()
 */
class ProductBarcodeController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $productBarcodesTable = $this->getFactory()->createProductBarcodeTable();

        return [
            'productBarcodesTable' => $productBarcodesTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $productBarcodeTable = $this->getFactory()->createProductBarcodeTable();

        return new JsonResponse(
            $productBarcodeTable->fetchData()
        );
    }
}
