<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class AddressController extends AbstractController
{
    protected const URL_CUSTOMER_LIST = '/customer';
    protected const URL_CUSTOMER_VIEW = '/customer/view';
    protected const ERROR_MESSAGE_CUSTOMER_ADDRESS_DOES_NOT_EXIST = 'Customer Address with ID = %d does not exist';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idCustomer = $request->query->getInt(CustomerConstants::PARAM_ID_CUSTOMER);
        $idCustomerAddress = $this->castId($request->query->get(CustomerConstants::PARAM_ID_CUSTOMER_ADDRESS));

        if (!$idCustomer) {
            return $this->redirectResponse(static::URL_CUSTOMER_LIST);
        }

        if (!$this->getFacade()->findCustomerAddressById($idCustomerAddress)) {
            $this->addErrorMessage(sprintf(static::ERROR_MESSAGE_CUSTOMER_ADDRESS_DOES_NOT_EXIST, $idCustomerAddress));

            return $this->redirectResponse(
                Url::generate(static::URL_CUSTOMER_VIEW, [
                    CustomerConstants::PARAM_ID_CUSTOMER => $idCustomer,
                ])->build()
            );
        }

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

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $customerAddress = new AddressTransfer();
            $customerAddress->fromArray($addressForm->getData(), true);

            $this->getFacade()->updateAddress($customerAddress);

            return $this->redirectResponse(sprintf(
                '/customer/view?%s=%d',
                CustomerConstants::PARAM_ID_CUSTOMER,
                $idCustomer
            ));
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
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get(CustomerConstants::PARAM_ID_CUSTOMER));

        $dataProvider = $this->getFactory()->createAddressFormDataProvider();
        $addressForm = $this
            ->getFactory()
            ->createAddressForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $addressTransfer = new AddressTransfer();
            $addressTransfer->fromArray($addressForm->getData(), true);
            $addressTransfer->setFkCustomer($idCustomer);

            $this->getFacade()->createAddress($addressTransfer);

            return $this->redirectResponse(
                sprintf('/customer/view?%s=%d', CustomerConstants::PARAM_ID_CUSTOMER, $idCustomer)
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
