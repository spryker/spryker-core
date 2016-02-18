<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Communication\Form\OrderItemSplitForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacade getFacade()
 */
class OrderItemSplitController extends AbstractController
{

    const SALES_ORDER_DETAIL_URL = '/sales/details?id-sales-order=%d';
    const SPLIT_SUCCESS_MESSAGE = 'Order item with "%d" was successfully split.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function splitAction(Request $request)
    {
        $orderItemForm = $this
            ->getFactory()
            ->createOrderItemSplitForm()
            ->handleRequest($request);

        $data = $orderItemForm->getData();

        if ($orderItemForm->isValid()) {
            $this->getFacade()
                ->splitSalesOrderItem($data[OrderItemSplitForm::FIELD_ID_ORDER_ITEM], $data[OrderItemSplitForm::FIELD_QUANTITY]);
        }

        return $this->redirectResponse(sprintf(self::SALES_ORDER_DETAIL_URL, $data[OrderItemSplitForm::FIELD_ID_ORDER]));
    }

}
