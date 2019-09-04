<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Controller;

use Spryker\Shared\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ShipmentGui\Communication\Exception\OrderNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\OrderNotFoundException
     *
     * @return array
     */
    public function thresholdExpensesAction(Request $request)
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer|null $orderTransfer */
        $orderTransfer = $request->attributes->get('orderTransfer');

        if ($orderTransfer === null) {
            throw new OrderNotFoundException();
        }

        return $this->viewResponse([
            'order' => $orderTransfer,
            'thresholdExpenseType' => SalesOrderThresholdGuiConfig::THRESHOLD_EXPENSE_TYPE,
        ]);
    }
}
