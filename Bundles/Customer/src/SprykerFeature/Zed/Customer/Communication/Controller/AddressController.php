<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Customer\CustomerConfig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerFacade getFacade()
 * @method CustomerDependencyContainer getDependencyContainer()
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
        $idCustomer = $request->get(CustomerConfig::PARAM_ID_CUSTOMER);

        $table = $this->getDependencyContainer()
            ->createCustomerAddressTable($idCustomer);

        return $this->viewResponse([
            'addressTable' => $table->render(),
            'idCustomer' => $idCustomer,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idCustomer = $request->get(CustomerConfig::PARAM_ID_CUSTOMER);

        $table = $this->getDependencyContainer()
            ->createCustomerAddressTable($idCustomer);

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
        $idCustomerAddress = $request->get(CustomerConfig::PARAM_ID_CUSTOMER_ADDRESS);

        $customerAddress = $this->createCustomerAddressTransfer();
        $customerAddress->setIdCustomerAddress($idCustomerAddress);

        $addressDetails = $this->getFacade()
            ->getAddress($customerAddress);

        if (empty($addressDetails) === false) {
            $idCustomer = $addressDetails->getFkCustomer();
        }

        $customerAddressTransfer = $this->createCustomerAddressTransfer();
        $customerAddressTransfer->setIdCustomerAddress($idCustomerAddress);

        $address = $this->getFacade()
            ->getAddress($customerAddressTransfer);

        return $this->viewResponse([
            'address' => $address->toArray(),
            'idCustomer' => $idCustomer,
            'idCustomerAddress' => $idCustomerAddress,
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
        $idCustomerAddress = $request->query->getInt(CustomerConfig::PARAM_ID_CUSTOMER_ADDRESS);

        $customerAddress = $this->createCustomerAddressTransfer();
        $customerAddress->setIdCustomerAddress($idCustomerAddress);

        $addressDetails = $this->getFacade()
            ->getAddress($customerAddress);

        if (!empty($addressDetails)) {
            $idCustomer = $addressDetails->getFkCustomer();
        }

        $addressForm = $this->getDependencyContainer()->createAddressForm();
        $addressForm->handleRequest($request);

        if ($addressForm->isValid()) {
            $customerAddress = $addressForm->getData();

            $this->getFacade()
                ->updateAddress($customerAddress);

            return $this->redirectResponse(sprintf(
                '/customer/address/?%s=%d', CustomerConfig::PARAM_ID_CUSTOMER, $idCustomer)
            );
        }

        return $this->viewResponse([
            'form' => $addressForm->createView(),
            'idCustomer' => $idCustomer,
            'idCustomerAddress' => $idCustomerAddress,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $idCustomer = $request->query->getInt(CustomerConfig::PARAM_ID_CUSTOMER);

        $addressForm = $this->getDependencyContainer()->createAddressForm();
        $addressForm->handleRequest($request);

        if ($addressForm->isValid()) {
            /* @var AddressTransfer $data */
            $customerAddress = $addressForm->getData();
            $customerAddress->setFkCustomer($idCustomer);

            $this->getFacade()
                ->createAddress($customerAddress);

            return $this->redirectResponse(
                sprintf('/customer/address/?%s=%d', CustomerConfig::PARAM_ID_CUSTOMER, $idCustomer)
            );
        }

        return $this->viewResponse([
            'form' => $addressForm->createView(),
            'idCustomer' => $idCustomer,
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
