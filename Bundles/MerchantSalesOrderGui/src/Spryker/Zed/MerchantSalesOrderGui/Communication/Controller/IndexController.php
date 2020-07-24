<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Communication\MerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Business\MerchantSalesOrderGuiFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $merchantTable = $this->getFactory()
            ->createMyOrderTable();

        return $this->viewResponse([
            'myOrders' => $merchantTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createMyOrderTable();

        return $this->jsonResponse($table->fetchData());
    }
}
