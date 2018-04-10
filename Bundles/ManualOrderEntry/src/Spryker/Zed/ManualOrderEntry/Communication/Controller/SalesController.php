<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Communication\Controller;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
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

        $orderSourceTransfer = $this->getRepository()
            ->getOrderSourceById($orderTransfer->getFkOrderSource());

        $orderSourceName = $this->getOrderSourceName($orderSourceTransfer);

        return $this->viewResponse([
            'orderSourceName' => $orderSourceName,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(Request $request)
    {
        return $request->request->get('orderTransfer');
    }

    /**
     * @param \Generated\Shared\Transfer\OrderSourceTransfer $orderSourceTransfer
     *
     * @return string
     */
    protected function getOrderSourceName(OrderSourceTransfer $orderSourceTransfer): string
    {
        return $orderSourceTransfer->getName() !== null ?
            $orderSourceTransfer->getName() : '-';
    }
}
