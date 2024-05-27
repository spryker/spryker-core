<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\MerchantCommissionGui\Communication\MerchantCommissionGuiCommunicationFactory getFactory()
 */
class ListController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        $merchantCommissionListTable = $this->getFactory()->createMerchantCommissionListTable();

        return $this->viewResponse([
            'merchantCommissionListTable' => $merchantCommissionListTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableDataAction(): JsonResponse
    {
        $merchantCommissionListTable = $this->getFactory()->createMerchantCommissionListTable();

        return $this->jsonResponse($merchantCommissionListTable->fetchData());
    }
}
