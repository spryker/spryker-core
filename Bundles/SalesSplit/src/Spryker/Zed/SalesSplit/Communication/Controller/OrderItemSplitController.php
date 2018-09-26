<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesSplit\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesSplit\Communication\Form\OrderItemSplitForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesSplit\Communication\SalesSplitCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesSplit\Business\SalesSplitFacadeInterface getFacade()
 */
class OrderItemSplitController extends AbstractController
{
    public const SALES_ORDER_DETAIL_URL = '/sales/detail?id-sales-order=%d';
    public const SPLIT_SUCCESS_MESSAGE = 'Order item with "%d" was successfully split.';

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

        $formData = $orderItemForm->getData();

        if ($orderItemForm->isSubmitted() && $orderItemForm->isValid()) {
            $itemSplitResponseTransfer = $this->getFacade()->splitSalesOrderItem(
                $formData[OrderItemSplitForm::FIELD_ID_ORDER_ITEM],
                $formData[OrderItemSplitForm::FIELD_QUANTITY]
            );

            if (!$itemSplitResponseTransfer->getSuccess()) {
                foreach ($itemSplitResponseTransfer->getValidationMessages() as $message) {
                    $this->addErrorMessage($message);
                }
            }
        }

        return $this->redirectResponse(sprintf(self::SALES_ORDER_DETAIL_URL, $formData[OrderItemSplitForm::FIELD_ID_ORDER]));
    }
}
