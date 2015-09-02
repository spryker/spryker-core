<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerCommunication getFactory()
 * @method CustomerQueryContainerInterface getQueryContainer()
 * @method CustomerDependencyContainer getDependencyContainer()
 * @method CustomerFacade getFacade()
 */
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

        $table = $this->getDependencyContainer()
            ->createCustomerAddressTable($idCustomer)
        ;

        return $this->viewResponse([
            'addressTable' => $table->render(),
            'id_customer' => $idCustomer,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        $table = $this->getDependencyContainer()
            ->createCustomerAddressTable($idCustomer)
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idCustomer = false;
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
        $idCustomer = false;
        $idCustomerAddress = $request->get('id_customer_address');

        $customerAddress = $this->createCustomerAddressTransfer();
        $customerAddress->setIdCustomerAddress($idCustomerAddress);

        $addressDetails = $this->getFacade()
            ->getAddress($customerAddress)
        ;

        if (false === empty($addressDetails)) {
            $idCustomer = $addressDetails->getFkCustomer();
        }

        $addressForm = $this->getDependencyContainer()
            ->createAddressForm('update')
        ;
        $addressForm->init();

        $addressForm->handleRequest();

        if (true === $addressForm->isValid()) {
            $data = $addressForm->getData();

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

        $addressForm = $this->getDependencyContainer()
            ->createAddressForm('add')
        ;

        $addressForm->handleRequest();

        if (true === $addressForm->isValid()) {
            $data = $addressForm->getData();
            $data['fk_customer'] = $idCustomer;

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
     * @return AddressTransfer
     */
    protected function createCustomerAddressTransfer()
    {
        return new AddressTransfer();
    }

}
