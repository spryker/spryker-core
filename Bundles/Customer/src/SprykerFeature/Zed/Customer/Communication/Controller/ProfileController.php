<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Customer\Business\Exception\AddressNotFoundException;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 * @method CustomerFacade getFacade()
 */
class ProfileController extends AbstractController
{

    /**
     * @param Request $request
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

        $form = $this->getDependencyContainer()
            ->createCustomerForm($request)
        ;

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);
        $customerTransfer = $this->getFacade()
            ->getCustomer($customerTransfer)
        ;

        try {
            $idShippingAddress = $this->getFacade()
                ->getDefaultShippingAddress($customerTransfer)
                ->getIdCustomerAddress()
            ;
        } catch (AddressNotFoundException $e) {
            $idShippingAddress = null;
        }

        try {
            $idBillingAddress = $this->getFacade()
                ->getDefaultBillingAddress($customerTransfer)
                ->getIdCustomerAddress()
            ;
        } catch (AddressNotFoundException $e) {
            $idBillingAddress = null;
        }

        $addresses = [];
        $addressesItems = $customerTransfer->getAddresses()
            ->getCustomerAddressItems()
        ;
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
            'id_customer' => $customerTransfer->getIdCustomer(),
            'customerJson' => json_encode($form->toArray()),
            'registered' => $customerTransfer->getRegistered(),
            'addresses' => $addresses,
            'form' => $form->renderDataForTwig()[AbstractForm::OUTPUT_PAYLOAD]['fields'],
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function sendPasswordRestoreTokenAction(Request $request)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($request->query->get('id'));
        $this->getFacade()
            ->forgotPassword($customerTransfer)
        ;

        return $this->redirectResponse('/customer/profile?id=' . $request->query->get('id'));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function editAction(Request $request)
    {
        $form = $this->getDependencyContainer()
            ->createCustomerForm($request)
        ;
        $form->init();

        if ($form->isValid()) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getRequestData());
            $this->getFacade()
                ->updateCustomer($customerTransfer)
            ;
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addressesAction(Request $request)
    {
        $grid = $this->getDependencyContainer()
            ->createAddressGrid($request)
        ;

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addressAction(Request $request)
    {
        $form = $this->getDependencyContainer()
            ->createAddressForm($request)
        ;
        $form->init();

        if ($form->isValid()) {
            $addressTransfer = new CustomerAddressTransfer();
            $addressTransfer->fromArray($form->getRequestData());
            if ($addressTransfer->getIdCustomerAddress()) {
                $this->getFacade()
                    ->updateAddress($addressTransfer)
                ;

                return $this->jsonResponse($form->renderData());
            }

            $this->getFacade()
                ->createAddress($addressTransfer)
            ;
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function setDefaultShippingAddressAction(Request $request)
    {
        $addressTransfer = new CustomerAddressTransfer();
        $addressTransfer->setIdCustomerAddress($request->query->get('address_id'));
        $addressTransfer->setFkCustomer($request->query->get('customer_id'));
        $this->getFacade()
            ->setDefaultShippingAddress($addressTransfer)
        ;

        return $this->redirectResponse('/customer/profile?id=' . $request->query->get('customer_id'));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function setDefaultBillingAddressAction(Request $request)
    {
        $addressTransfer = new CustomerAddressTransfer();
        $addressTransfer->setIdCustomerAddress($request->query->get('address_id'));
        $addressTransfer->setFkCustomer($request->query->get('customer_id'));
        $this->getFacade()
            ->setDefaultBillingAddress($addressTransfer)
        ;

        return $this->redirectResponse('/customer/profile?id=' . $request->query->get('customer_id'));
    }

}
