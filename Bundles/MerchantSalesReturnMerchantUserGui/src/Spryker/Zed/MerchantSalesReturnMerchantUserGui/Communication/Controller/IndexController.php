<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\MerchantSalesReturnMerchantUserGuiCommunicationFactory getFactory()
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
        $myReturnsTable = $this->getFactory()
            ->createMerchantReturnTable();

        return $this->viewResponse([
            'myReturnsTable' => $myReturnsTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createMerchantReturnTable();

        return $this->jsonResponse($table->fetchData());
    }
}
