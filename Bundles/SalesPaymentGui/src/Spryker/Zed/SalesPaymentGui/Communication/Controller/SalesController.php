<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentGui\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function listAction(Request $request)
    {
        $orderTransfer = $this->getOrder($request);

        return [
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
