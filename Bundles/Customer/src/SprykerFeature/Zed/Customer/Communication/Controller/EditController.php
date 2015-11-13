<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerTypeForm;
use SprykerFeature\Zed\Customer\CustomerConfig;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method CustomerCommunication getFactory()
 * @method CustomerQueryContainerInterface getQueryContainer()
 * @method CustomerDependencyContainer getDependencyContainer()
 * @method CustomerFacade getFacade()
 */
class EditController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get(CustomerTypeForm::PARAM_ID_CUSTOMER);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);
        $customer = $this->getFacade()->getCustomer($customerTransfer);

        $customerTransfer = $customerTransfer->fromArray($customer->toArray(), true);

        $form = $this->getDependencyContainer()
            ->createCustomerForm($customerTransfer, CustomerTypeForm::UPDATE, $idCustomer)
        ;

        $form->handleRequest($request);

        if ($form->isValid() === true) {
            $data = $form->getData();

            $customer = $this->createCustomerTransfer();
            $customer->fromArray($data, true);
            $this->getFacade()
                ->updateCustomer($customer)
            ;

            $defaultBilling = !empty($data[CustomerTransfer::DEFAULT_BILLING_ADDRESS]) ? $data[CustomerTransfer::DEFAULT_BILLING_ADDRESS] : false;
            if (empty($defaultBilling) === false) {
                $this->updateBillingAddress($idCustomer, $defaultBilling);
            }

            $defaultShipping = !empty($data[CustomerTransfer::DEFAULT_SHIPPING_ADDRESS]) ? $data[CustomerTransfer::DEFAULT_SHIPPING_ADDRESS] : false;
            if (empty($defaultShipping) === false) {
                $this->updateShippingAddress($idCustomer, $defaultShipping);
            }

            return $this->redirectResponse(sprintf('/customer/view/?%s=%d', CustomerConfig::PARAM_ID_CUSTOMER, $idCustomer));
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCustomer' => $idCustomer,
        ]);
    }

    /**
     * @return CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

    /**
     * @return AddressTransfer
     */
    protected function createAddressTransfer()
    {
        return new AddressTransfer();
    }

    /**
     * @param int $idCustomer
     * @param int $defaultBillingAddress
     */
    protected function updateBillingAddress($idCustomer, $defaultBillingAddress)
    {
        $addressTransfer = $this->createCustomAddressTransfer($idCustomer, $defaultBillingAddress);
        $this->getFacade()
            ->setDefaultBillingAddress($addressTransfer)
        ;
    }

    /**
     * @param int $idCustomer
     * @param int $defaultShippingAddress
     */
    protected function updateShippingAddress($idCustomer, $defaultShippingAddress)
    {
        $addressTransfer = $this->createCustomAddressTransfer($idCustomer, $defaultShippingAddress);
        $this->getFacade()
            ->setDefaultShippingAddress($addressTransfer)
        ;
    }

    /**
     * @param int $idCustomer
     * @param int $billingAddress
     *
     * @return AddressTransfer
     */
    protected function createCustomAddressTransfer($idCustomer, $billingAddress)
    {
        $addressTransfer = $this->createAddressTransfer();

        $addressTransfer->setIdCustomerAddress($billingAddress);
        $addressTransfer->setFkCustomer($idCustomer);

        return $addressTransfer;
    }

}
