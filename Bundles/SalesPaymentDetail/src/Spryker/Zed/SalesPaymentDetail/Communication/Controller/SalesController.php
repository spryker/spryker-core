<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesPaymentDetail\Business\SalesPaymentDetailFacadeInterface getFacade()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function listAction(Request $request): array
    {
        $orderTransfer = $this->getOrderTransfer($request);

        $salesPaymentDetailTransfer = $this->getRepository()
            ->findByEntityReference((string)$orderTransfer->getOrderReference());

        return $this->viewResponse([
            'salesPaymentDetail' => $salesPaymentDetailTransfer ? $salesPaymentDetailTransfer->toArray() : null,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(Request $request): OrderTransfer
    {
        if (!$request->request->has('serializedOrderTransfer')) {
            throw new InvalidArgumentException('`serializedOrderTransfer` not found in request');
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->unserialize((string)$request->request->get('serializedOrderTransfer'));

        return $orderTransfer;
    }
}
