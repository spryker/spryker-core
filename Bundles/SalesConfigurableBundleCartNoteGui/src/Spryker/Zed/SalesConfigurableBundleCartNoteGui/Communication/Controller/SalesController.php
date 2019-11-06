<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundleCartNoteGui\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[][]
     */
    public function listAction(Request $request): array
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $request->request->get('orderTransfer');

        return [
            'salesOrderConfiguredBundleCollection' => $this->getSalesOrderConfiguredBundleCollectionByOrderTransfer($orderTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    protected function getSalesOrderConfiguredBundleCollectionByOrderTransfer(OrderTransfer $orderTransfer): array
    {
        $salesOrderConfiguredBundleCollection = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $salesOrderConfiguredBundle = $itemTransfer->getSalesOrderConfiguredBundle();
            if ($salesOrderConfiguredBundle && $salesOrderConfiguredBundle->getCartNote()) {
                $salesOrderConfiguredBundleCollection[$salesOrderConfiguredBundle->getIdSalesOrderConfiguredBundle()] = $salesOrderConfiguredBundle;
            }
        }

        return $salesOrderConfiguredBundleCollection;
    }
}
