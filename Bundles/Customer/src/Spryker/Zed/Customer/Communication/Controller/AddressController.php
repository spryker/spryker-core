<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
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
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idCustomer = false;
        $idCustomerAddress = $this->castId($request->query->get(CustomerConstants::PARAM_ID_CUSTOMER_ADDRESS));

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
