<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\MerchantSalesOrderMerchantUserGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @phpstan-return array<string, mixed>
     *
     * @return array
     */
    public function indexAction(): array
    {
        $myOrdersTable = $this->getFactory()
            ->createMerchantOrderTable();

        return $this->viewResponse([
            'myOrdersTable' => $myOrdersTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createMerchantOrderTable();

        return $this->jsonResponse($table->fetchData());
    }
}
