<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $merchantRelationshipSalesOrderThresholdTable = $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdTable();

        return $this->viewResponse([
            'merchantRelationships' => $merchantRelationshipSalesOrderThresholdTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $merchantRelationshipSalesOrderThresholdTable = $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdTable();

        return $this->jsonResponse($merchantRelationshipSalesOrderThresholdTable->fetchData());
    }
}
