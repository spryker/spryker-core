<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

/**
 * @method SalesFacade getFacade()
 */
class OrderItemSplitController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function splitAction()
    {
        $orderItemForm = $orderItemSplitForm = $this->getDependencyContainer()->getOrderItemSplitForm()->init();
        $orderItemForm->handleRequest();
        $data = $orderItemForm->getData();

        if ($orderItemForm->isValid()) {
            $splitResponseTransfer = $this->getFacade()
                ->splitSalesOrderItem($data['id_order_item'], $data['quantity']);

            //@todo clarify twig extension behaviour
            /*if (!$splitResponseTransfer->getSuccess()) {
                $this->addMessageError(implode('<br />', $splitResponseTransfer->getValidationMessages()));
            } else {
                $this->addMessageSuccess(
                    sprintf('Order item with "%d" was successfully split', $data['id_order_item'])
                );
            }*/
        }

         return $this->redirectResponse(sprintf('/sales/details?id-sales-order=%d', $data['id_order']));
    }
}

