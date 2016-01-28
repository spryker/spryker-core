<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class AddressController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get(CustomerConstants::PARAM_ID_CUSTOMER);

        $table = $this->getFactory()
            ->createCustomerAddressTable($idCustomer);

        return $this->viewResponse([
            'addressTable' => $table->render(),
            'idCustomer' => $idCustomer,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idCustomer = $request->get(CustomerConstants::PARAM_ID_CUSTOMER);

        $table = $this->getFactory()
            ->createCustomerAddressTable($idCustomer);

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idCustomer = false;
        $idCustomerAddress = $request->get(CustomerConstants::PARAM_ID_CUSTOMER_ADDRESS);

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idCustomer = false;
        $idCustomerAddress = $request->query->getInt(CustomerConstants::PARAM_ID_CUSTOMER_ADDRESS);

        $customerAddress = $this->createCustomerAddressTransfer();
        $customerAddress->setIdCustomerAddress($idCustomerAddress);

        $addressDetails = $this->getFacade()
            ->getAddress($customerAddress);

        if (!empty($addressDetails)) {
            $idCustomer = $addressDetails->getFkCustomer();
        }

        $dataProvider = $this->getFactory()->createAddressFormDataProvider();
        $addressForm = $this
            ->getFactory()
            ->createAddressForm(
                $dataProvider->getData($idCustomerAddress),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($addressForm->isValid()) {
            $customerAddress = new AddressTransfer();
            $customerAddress->fromArray($addressForm->getData(), true);

            $this->getFacade()->updateAddress($customerAddress);

            return $this->redirectResponse(sprintf(
                '/customer/address/?%s=%d', CustomerConstants::PARAM_ID_CUSTOMER, $idCustomer)
            );
        }

        return $this->viewResponse([
            'form' => $addressForm->createView(),
            'idCustomer' => $idCustomer,
            'idCustomerAddress' => $idCustomerAddress,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $idCustomer = $request->query->getInt(CustomerConstants::PARAM_ID_CUSTOMER);

        $dataProvider = $this->getFactory()->createAddressFormDataProvider();
        $addressForm = $this
            ->getFactory()
            ->createAddressForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($addressForm->isValid()) {
            $addressTransfer = new AddressTransfer();
            $addressTransfer->fromArray($addressForm->getData(), true);
            $addressTransfer->setFkCustomer($idCustomer);

            $this->getFacade()->createAddress($addressTransfer);

            return $this->redirectResponse(
                sprintf('/customer/address/?%s=%d', CustomerConstants::PARAM_ID_CUSTOMER, $idCustomer)
            );
        }

        return $this->viewResponse([
            'form' => $addressForm->createView(),
            'idCustomer' => $idCustomer,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createCustomerAddressTransfer()
    {
        return new AddressTransfer();
    }

}
