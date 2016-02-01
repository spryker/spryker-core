<?php

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Business\SalesFacade;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory;

/**
 * @method SalesCommunicationFactory getFactory()
 * @method SalesFacade getFacade()
 */
class EditController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function customerAction(Request $request)
    {
        $idOrder = $request->get('id-sales-order');
        $form = $this->getFactory()
            ->createCustomerForm($idOrder);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $orderTransfer = (new OrderTransfer())->fromArray($form->getData(), true);
            $this->getFacade()
                ->updateOrderCustomer($orderTransfer, $idOrder);

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function addressAction(Request $request)
    {
        $idOrder = $request->get('id-sales-order');
        $idOrderAddress = $request->get('id-address');

        $form = $this->getFactory()
            ->createAddressForm($idOrderAddress);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $addressTransfer = (new AddressTransfer())->fromArray($form->getData(), true);
            $this->getFacade()
                ->updateOrderAddress($addressTransfer, $idOrderAddress);

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }

}
