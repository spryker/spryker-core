<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\Form\CustomerForm;
use Symfony\Component\HttpFoundation\Request;

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
        $form->init();
        $form->handleRequest();

        if ($request->isMethod('POST') && $form->isValid()) {

            $orderTransfer = $form->getData();
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

        $form = $this->getDependencyContainer()->createAddressForm($idOrderAddress)->init();
        $form->handleRequest();

        if ($request->isMethod('POST') && $form->isValid()) {

            $addressTransfer = $form->getData();
            $this->getFacade()->updateOrderAddress($addressTransfer, $idOrderAddress);

            $this->redirectResponse(sprintf('/sales/edit/customer?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }
}
