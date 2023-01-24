<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Communication\Controller;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ManualOrderEntry\Business\Exception\OrderSourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryRepositoryInterface getRepository()
 * @method \Spryker\Zed\ManualOrderEntry\Business\ManualOrderEntryFacadeInterface getFacade()
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
        $orderTransfer = $this->getOrderTransfer($request);

        try {
            $orderSourceTransfer = $this->getRepository()
                ->getOrderSourceById($orderTransfer->getFkOrderSource());
            $orderSourceName = $this->getOrderSourceName($orderSourceTransfer);
        } catch (OrderSourceNotFoundException $e) {
            $orderSourceName = '-';
        }

        return $this->viewResponse([
            'orderSourceName' => $orderSourceName,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(Request $request)
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

    /**
     * @param \Generated\Shared\Transfer\OrderSourceTransfer $orderSourceTransfer
     *
     * @return string
     */
    protected function getOrderSourceName(OrderSourceTransfer $orderSourceTransfer): string
    {
        return $orderSourceTransfer->getName() ?? '-';
    }
}
