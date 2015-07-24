<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesAddressTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class EditController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function customerAction(Request $request)
    {
        $idOrder = $request->get('id-sales-order');
        $form = $this->getDependencyContainer()->createCustomerForm($idOrder);
        $form->handleRequest();

        if ($request->isMethod('POST') && $form->isValid()) {

            $orderTransfer = (new OrderTransfer())->fromArray($form->getData(), true);
            $this->getFacade()->updateOrderCustomer($orderTransfer, $idOrder);

            $this->redirectResponse(sprintf('/sales/edit/customer?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function addressAction(Request $request)
    {
        $idOrder = $request->get('id-sales-order');
        $idOrderAddress = $request->get('id-address');

        $form = $this->getDependencyContainer()->createAddressForm($idOrderAddress);
        $form->handleRequest();

        if ($request->isMethod('POST') && $form->isValid()) {

            $addressTransfer = (new SalesAddressTransfer())->fromArray($form->getData(), true);
            $this->getFacade()->updateOrderAddress($addressTransfer, $idOrderAddress);

            $this->redirectResponse(sprintf('/sales/edit/customer?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }
}
