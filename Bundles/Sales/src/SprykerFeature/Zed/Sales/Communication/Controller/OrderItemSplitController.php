<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Business\SalesFacade;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use SprykerFeature\Zed\Sales\Communication\Form\OrderItemSplitForm;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 * @method SalesFacade getFacade()
 */
class OrderItemSplitController extends AbstractController
{
    const SALES_ORDER_DETAIL_URL = '/sales/details?id-sales-order=%d';
    const SPLIT_SUCCESS_MESSAGE = 'Order item with "%d" was successfully split.';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function splitAction()
    {
        $orderItemForm = $orderItemSplitForm = $this->getDependencyContainer()->getOrderItemSplitForm();
        $orderItemForm->handleRequest();
        $data = $orderItemForm->getData();

        if ($orderItemForm->isValid()) {
            $splitResponseTransfer = $this->getFacade()
                ->splitSalesOrderItem($data[OrderItemSplitForm::ID_ORDER_ITEM], $data[OrderItemSplitForm::QUANTITY]);

          /*  if (!$splitResponseTransfer->getSuccess()) {
                $this->addMessageError($splitResponseTransfer->getValidationMessages());
            } else {
                $this->addMessageSuccess($splitResponseTransfer->getSuccessMessage());
            }*/
        }

        return $this->redirectResponse(sprintf(self::SALES_ORDER_DETAIL_URL, $data[OrderItemSplitForm::ID_ORDER]));
    }
}

