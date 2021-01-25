<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request): array
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $request->request->get('orderTransfer');

        $orderReturnTable = $this->getFactory()
            ->createOrderReturnTable($orderTransfer);

        return [
            'orderReturnTable' => $orderReturnTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $idSalesOrder = $request->query->getInt(OrderReturnTable::PARAM_ID_ORDER) ?: null;

        $orderReturnTable = $this->getFactory()
            ->createOrderReturnTable((new OrderTransfer())->setIdSalesOrder($idSalesOrder));

        return new JsonResponse(
            $orderReturnTable->fetchData()
        );
    }
}
