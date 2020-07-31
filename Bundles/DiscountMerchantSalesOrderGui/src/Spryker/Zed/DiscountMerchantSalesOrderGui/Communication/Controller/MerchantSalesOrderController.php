<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrderGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\DiscountMerchantSalesOrderGui\Communication\DiscountMerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountMerchantSalesOrderGui\Business\DiscountMerchantSalesOrderGuiFacadeInterface getFacade()
 */
class MerchantSalesOrderController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        /**
         * @var \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
         */
        $merchantOrderTransfer = $request->request->get('merchantOrderTransfer');

        $merchantOrderItemIds = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            $merchantOrderItemIds[] = $merchantOrderItem->getIdOrderItem();
        }

        $calculatedDiscountTransfers = array_filter(
            $merchantOrderTransfer->getOrder()->getCalculatedDiscounts()->getArrayCopy(),
            function (CalculatedDiscountTransfer $calculatedDiscountTransfer) use ($merchantOrderItemIds) {
                return $calculatedDiscountTransfer->getFkSalesOrderItem() === null || in_array($calculatedDiscountTransfer->getFkSalesOrderItem(), $merchantOrderItemIds);
            }
        );

        $merchantOrderTransfer->getOrder()->setCalculatedDiscounts(
            new ArrayObject($calculatedDiscountTransfers)
        );
        // END

        return $this->renderView(
            '@Discount/Sales/list.twig',
            [
                'order' => $merchantOrderTransfer->getOrder(),
            ]
        );
    }
}
