<?php

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\Exception\AddressNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
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
        $this->addBreadcrumb('Customer ID '.$idCustomer, '/customer/profile?id='.$idCustomer);

        $this->setMenuHighlight($customerUri);

        $form = $this->getDependencyContainer()->createCustomerForm($request);
        $form->init();

        $customerTransfer = new \Generated\Shared\Transfer\CustomerCustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);
        $customerTransfer = $this->getLocator()->customer()->facade()->getCustomer($customerTransfer);

        try {
            $idShippingAddress = $this->getLocator()->customer()->facade()
                ->getDefaultShippingAddress($customerTransfer)
                ->getIdCustomerAddress();
        } catch (AddressNotFoundException $e) {
            $idShippingAddress = null;
        }

        try {
            $idBillingAddress = $this->getLocator()->customer()->facade()
                ->getDefaultBillingAddress($customerTransfer)
                ->getIdCustomerAddress();
        } catch (AddressNotFoundException $e) {
            $idBillingAddress = null;
        }

        $addresses = [];
        foreach ($customerTransfer->getAddresses() as $address) {
            $addresses[] = [
                'id' => $address->getIdCustomerAddress(),
                'rendered' => $this->getLocator()->customer()->facade()->renderAddress($address),
                'isDefaultBilling' => ($address->getIdCustomerAddress() == $idBillingAddress),
                'isDefaultShipping' => ($address->getIdCustomerAddress() == $idShippingAddress),
            ];
        }

        return [
            'id_customer' => $customerTransfer->getIdCustomer(),
            'customerJson' => json_encode($form->toArray()),
            'registered' => $customerTransfer->getRegistered(),
            'addresses' => $addresses,
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function sendPasswordRestoreTokenAction(Request $request)
    {
        $customerTransfer = new \Generated\Shared\Transfer\CustomerCustomerTransfer();
        $customerTransfer->setIdCustomer($request->query->get('id'));
        $this->getLocator()->customer()->facade()->forgotPassword($customerTransfer);

        return $this->redirectResponse('/customer/profile?id='.$request->query->get('id'));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function editAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createCustomerForm($request);
        $form->init();

        if ($form->isValid()) {
            $customerTransfer = new \Generated\Shared\Transfer\CustomerCustomerTransfer();
            $customerTransfer->fromArray($form->getRequestData());
            $this->getLocator()->customer()->facade()->updateCustomer($customerTransfer);
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
        $grid = $this->getDependencyContainer()->createAddressGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addressAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createAddressForm($request);
        $form->init();

        if ($form->isValid()) {
            $addressTransfer = new \Generated\Shared\Transfer\CustomerAddressTransfer();
            $addressTransfer->fromArray($form->getRequestData());
            if ($addressTransfer->getIdCustomerAddress()) {
                $this->getLocator()->customer()->facade()->updateAddress($addressTransfer);

                return $this->jsonResponse($form->renderData());
            }

            $this->getLocator()->customer()->facade()->newAddress($addressTransfer);
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
        $addressTransfer = new \Generated\Shared\Transfer\CustomerAddressTransfer();
        $addressTransfer->setIdCustomerAddress($request->query->get('address_id'));
        $addressTransfer->setFkCustomer($request->query->get('customer_id'));
        $this->getLocator()->customer()->facade()->setDefaultShippingAddress($addressTransfer);

        return $this->redirectResponse('/customer/profile?id='.$request->query->get('customer_id'));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function setDefaultBillingAddressAction(Request $request)
    {
        $addressTransfer = new \Generated\Shared\Transfer\CustomerAddressTransfer();
        $addressTransfer->setIdCustomerAddress($request->query->get('address_id'));
        $addressTransfer->setFkCustomer($request->query->get('customer_id'));
        $this->getLocator()->customer()->facade()->setDefaultBillingAddress($addressTransfer);

        return $this->redirectResponse('/customer/profile?id='.$request->query->get('customer_id'));
    }
}
