<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AddressController extends AbstractController
{

    public function indexAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        /** @var CustomerTable $table */
        $table = $this->getDependencyContainer()->createCustomerAddressTable($idCustomer);
        $table->init();

        return $this->viewResponse([
            'addressTable' => $table,
            'id_customer' => $idCustomer,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        /** @var CustomerTable $table */
        $table = $this->getDependencyContainer()->createCustomerAddressTable($idCustomer);
        $table->init();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    public function viewAction(Request $request)
    {
        $idCustomerAddress = $request->get('id_customer_address');

        return $this->viewResponse([
            'id_customer_address' => $idCustomerAddress,
        ]);
    }

    public function editAction()
    {
        $idCustomerAddress = $request->get('id_customer_address');

        return $this->viewResponse([
            'id_customer_address' => $idCustomerAddress,
        ]);
    }

    public function addAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        /** @var CustomerForm $addressForm */
        $addressForm = $this->getDependencyContainer()->createAddressForm('add');
        $addressForm->init();

        $addressForm->handleRequest();

        if ($addressForm->isValid()) {
            $data = $addressForm->getData();

            /** @var CustomerTransfer $customer */
            $customer = $this->createCustomerTransfer();
            $customer->fromArray($data, true);

            $lastInsertId = $this->getFacade()->registerCustomer($customer);
            if ($lastInsertId) {
                $this->redirectResponse(sprintf('/customer/view?id_customer=%d', $lastInsertId));
            }
        }

        return $this->viewResponse([
            'form' => $addressForm->createView(),
            'id_customer' => $idCustomer,
        ]);
    }

}
