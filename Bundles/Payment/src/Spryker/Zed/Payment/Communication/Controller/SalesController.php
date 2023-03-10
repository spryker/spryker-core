<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use {@link \Spryker\Zed\SalesPaymentGui\Communication\Controller\SalesController} instead.
 *
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface getRepository()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $orderTransfer = $this->getOrder($request);

        return [
            'payments' => $orderTransfer->getPayments(),
            'order' => $orderTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrder(Request $request): OrderTransfer
    {
        // @deprecated Exists for BC reasons. Will be removed in the next major release.
        if ($request->request->has('orderTransfer')) {
            /** @phpstan-var \Generated\Shared\Transfer\OrderTransfer */
            return $request->request->get('orderTransfer');
        }

        if (!$request->request->has('serializedOrderTransfer')) {
            throw new InvalidArgumentException('`serializedOrderTransfer` not found in request');
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->unserialize((string)$request->request->get('serializedOrderTransfer'));

        return $orderTransfer;
    }
}
