<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\Exception\AddressNotFoundException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class ProfileController extends AbstractController
{
    /**
     * @todo fix usage of old forms based on angularJs
     */
    const OUTPUT_PAYLOAD = 'content';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get('id'));

        $form = $this->getFactory()
            ->createCustomerForm($idCustomer);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);
        $customerTransfer = $this->getFacade()
            ->getCustomer($customerTransfer);

        try {
            $idShippingAddress = $this->getFacade()
                ->getDefaultShippingAddress($customerTransfer)
                ->getIdCustomerAddress();
        } catch (AddressNotFoundException $e) {
            $idShippingAddress = null;
        }

        try {
            $idBillingAddress = $this->getFacade()
                ->getDefaultBillingAddress($customerTransfer)
                ->getIdCustomerAddress();
        } catch (AddressNotFoundException $e) {
            $idBillingAddress = null;
        }

        $addresses = [];
        $addressesItems = $customerTransfer->getAddresses()
            ->getAddresses();
        foreach ($addressesItems as $address) {
            $addresses[] = [
                'id' => $address->getIdCustomerAddress(),
                'first_name' => $address->getFirstName(),
                'last_name' => $address->getLastName(),
                'address1' => $address->getAddress1(),
                'address2' => $address->getAddress2(),
                'address3' => $address->getAddress3(),
                'company' => $address->getCompany(),
                'zipCode' => $address->getZipCode(),
                'city' => $address->getCity(),
                'isDefaultBilling' => ($address->getIdCustomerAddress() === $idBillingAddress),
                'isDefaultShipping' => ($address->getIdCustomerAddress() === $idShippingAddress),
            ];
        }

        return [
            'idCustomer' => $customerTransfer->getIdCustomer(),
            'customerJson' => json_encode($form->toArray()),
            'registered' => $customerTransfer->getRegistered(),
            'addresses' => $addresses,
            'form' => $form->renderDataForTwig()[self::OUTPUT_PAYLOAD]['fields'],
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendPasswordRestoreTokenAction(Request $request)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($this->castId($request->query->get('id')));
        $this->getFacade()
            ->sendPasswordRestoreMail($customerTransfer);

        return $this->redirectResponse('/customer/profile?id=' . $this->castId($request->query->get('id')));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request)
    {
        $form = $this->getFactory()
            ->createCustomerForm($request);

        if ($form->isValid() === true) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getRequestData());
            $this->getFacade()
                ->updateCustomer($customerTransfer);
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addressAction(Request $request)
    {
        $form = $this->getFactory()
            ->createAddressForm($request);

        if ($form->isValid() === true) {
            $addressTransfer = new AddressTransfer();
            $addressTransfer->fromArray($form->getRequestData());
            if ($addressTransfer->getIdCustomerAddress()) {
                $this->getFacade()
                    ->updateAddress($addressTransfer);

                return $this->jsonResponse($form->renderData());
            }

            $this->getFacade()
                ->createAddress($addressTransfer);
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setDefaultShippingAddressAction(Request $request)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIdCustomerAddress($this->castId($request->query->get('address_id')));
        $addressTransfer->setFkCustomer($this->castId($request->query->get('customer_id')));
        $this->getFacade()
            ->setDefaultShippingAddress($addressTransfer);

        return $this->redirectResponse('/customer/profile?id=' . $this->castId($request->query->get('customer_id')));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setDefaultBillingAddressAction(Request $request)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIdCustomerAddress($this->castId($request->query->get('address_id')));
        $addressTransfer->setFkCustomer($this->castId($request->query->get('customer_id')));
        $this->getFacade()
            ->setDefaultBillingAddress($addressTransfer);

        return $this->redirectResponse('/customer/profile?id=' . $this->castId($request->query->get('customer_id')));
    }
}
