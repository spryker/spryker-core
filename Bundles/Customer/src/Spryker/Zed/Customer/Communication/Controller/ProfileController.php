<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Customer\Business\Exception\AddressNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacade getFacade()
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
        $idCustomer = $request->query->get('id');
        $customerUri = '/customer';

        $this->clearBreadcrumbs();
        $this->addBreadcrumb('Customer', $customerUri);
        $this->addBreadcrumb('Customer ID ' . $idCustomer, '/customer/profile?id=' . $idCustomer);

        $this->setMenuHighlight($customerUri);

        $form = $this->getFactory()
            ->createCustomerForm($request);

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
        $customerTransfer->setIdCustomer($request->query->get('id'));
        $this->getFacade()
            ->sendPasswordRestoreMail($customerTransfer);

        return $this->redirectResponse('/customer/profile?id=' . $request->query->get('id'));
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
        $addressTransfer->setIdCustomerAddress($request->query->get('address_id'));
        $addressTransfer->setFkCustomer($request->query->get('customer_id'));
        $this->getFacade()
            ->setDefaultShippingAddress($addressTransfer);

        return $this->redirectResponse('/customer/profile?id=' . $request->query->get('customer_id'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setDefaultBillingAddressAction(Request $request)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIdCustomerAddress($request->query->get('address_id'));
        $addressTransfer->setFkCustomer($request->query->get('customer_id'));
        $this->getFacade()
            ->setDefaultBillingAddress($addressTransfer);

        return $this->redirectResponse('/customer/profile?id=' . $request->query->get('customer_id'));
    }

}
