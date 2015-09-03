<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Business\SalesFacade;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 * @method SalesFacade getFacade()
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
        $form = $this->getDependencyContainer()
            ->createCustomerForm($idOrder)
        ;
        $form->handleRequest();

        if ($form->isValid()) {

            $orderTransfer = (new OrderTransfer())->fromArray($form->getData(), true);
            $this->getFacade()
                ->updateOrderCustomer($orderTransfer, $idOrder)
            ;

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
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

        $form = $this->getDependencyContainer()
            ->createAddressForm($idOrderAddress)
        ;
        $form->handleRequest();

        if ($form->isValid()) {

            $addressTransfer = (new AddressTransfer())->fromArray($form->getData(), true);
            $this->getFacade()
                ->updateOrderAddress($addressTransfer, $idOrderAddress)
            ;

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }

}
