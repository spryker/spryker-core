<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AddressController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        /** @var CustomerTable $table */
        $table = $this->getDependencyContainer()
            ->createCustomerAddressTable($idCustomer)
        ;
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
        $table = $this->getDependencyContainer()
            ->createCustomerAddressTable($idCustomer)
        ;
        $table->init();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idCustomerAddress = $request->get('id_customer_address');

        $customerAddress = $this->createCustomerAddressTransfer();
        $customerAddress->setIdCustomerAddress($idCustomerAddress);

        $addressDetails = $this->getFacade()
            ->getAddress($customerAddress)
        ;
        if (false === empty($addressDetails)) {
            $idCustomer = $addressDetails->getFkCustomer();
        }

        $customerAddressTransfer = $this->createCustomerAddressTransfer();
        $customerAddressTransfer->setIdCustomerAddress($idCustomerAddress);

        $address = $this->getFacade()
            ->getAddress($customerAddressTransfer)
        ;

        return $this->viewResponse([
            'address' => $address->toArray(),
            'id_customer' => $idCustomer,
            'id_customer_address' => $idCustomerAddress,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idCustomerAddress = $request->get('id_customer_address');

        $customerAddress = $this->createCustomerAddressTransfer();
        $customerAddress->setIdCustomerAddress($idCustomerAddress);

        $addressDetails = $this->getFacade()
            ->getAddress($customerAddress)
        ;
        if (false === empty($addressDetails)) {
            $idCustomer = $addressDetails->getFkCustomer();
        }

        /** @var AddressForm $addressForm */
        $addressForm = $this->getDependencyContainer()
            ->createAddressForm('update')
        ;
        $addressForm->init();

        $addressForm->handleRequest();

        if ($addressForm->isValid()) {
            $data = $addressForm->getData();

            /** @var CustomerAddressTransfer $customerAddress */
            $customerAddress = $this->createCustomerAddressTransfer();
            $customerAddress->fromArray($data, true);

            $this->getFacade()
                ->updateAddress($customerAddress)
            ;

            return $this->redirectResponse(sprintf('/customer/address/?id_customer=%d', $idCustomer));
        }

        return $this->viewResponse([
            'form' => $addressForm->createView(),
            'id_customer' => $idCustomer,
            'id_customer_address' => $idCustomerAddress,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $idCustomer = intval($request->get('id_customer'));

        /** @var AddressForm $addressForm */
        $addressForm = $this->getDependencyContainer()
            ->createAddressForm('add')
        ;
        $addressForm->init();

        $addressForm->handleRequest();

        if ($addressForm->isValid()) {
            $data = $addressForm->getData();
            $data['fk_customer'] = $idCustomer;

            /** @var CustomerAddressTransfer $customerAddress */
            $customerAddress = $this->createCustomerAddressTransfer();
            $customerAddress->fromArray($data, true);

            $this->getFacade()
                ->createAddress($customerAddress)
            ;

            return $this->redirectResponse(sprintf('/customer/address/?id_customer=%d', $idCustomer));
        }

        return $this->viewResponse([
            'form' => $addressForm->createView(),
            'id_customer' => $idCustomer,
        ]);
    }

    /**
     * @return CustomerTransfer
     */
    protected function createCustomerAddressTransfer()
    {
        return new CustomerAddressTransfer();
    }

}
