<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductListGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $table = $this->getFactory()->createProductListTable();

        return [
            'table' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $productListTable = $this->getFactory()->createProductListTable();

        return $this->jsonResponse(
            $productListTable->fetchData()
        );
    }
}
