<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory;
use Spryker\Zed\Sales\Communication\Form\OrderItemSplitForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesCommunicationFactory getFactory()
 * @method SalesFacade getFacade()
 */
class OrderItemSplitController extends AbstractController
{

    const SALES_ORDER_DETAIL_URL = '/sales/details?id-sales-order=%d';
    const SPLIT_SUCCESS_MESSAGE = 'Order item with "%d" was successfully split.';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function splitAction(Request $request)
    {
        $orderItemForm = $orderItemSplitForm = $this->getFactory()->getOrderItemSplitForm();
        $orderItemForm->handleRequest($request);
        $data = $orderItemForm->getData();

        if ($orderItemForm->isValid()) {
            $this->getFacade()
                ->splitSalesOrderItem($data[OrderItemSplitForm::FIELD_ID_ORDER_ITEM], $data[OrderItemSplitForm::FIELD_QUANTITY]);
        }

        return $this->redirectResponse(sprintf(self::SALES_ORDER_DETAIL_URL, $data[OrderItemSplitForm::FIELD_ID_ORDER]));
    }

}
