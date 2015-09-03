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
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
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
        $idCustomer = $request->get('id_customer');

        /** @var CustomerForm $form */
        $form = $this->getDependencyContainer()
            ->createCustomerForm('update')
        ;

        $form->handleRequest();

        if (true === $form->isValid()) {
            $data = $form->getData();

            /** @var CustomerTransfer $customer */
            $customer = $this->createCustomerTransfer();
            $customer->fromArray($data, true);
            $this->getFacade()
                ->updateCustomer($customer)
            ;

            $defaultBilling = !empty($data['default_billing_address']) ? $data['default_billing_address'] : false;
            if (!empty($defaultBilling)) {
                $this->updateBillingAddress($idCustomer, $defaultBilling);
            }

            $defaultShipping = !empty($data['default_shipping_address']) ? $data['default_shipping_address'] : false;
            if (!empty($defaultShipping)) {
                $this->updateShippingAddress($idCustomer, $defaultShipping);
            }

            return $this->redirectResponse(sprintf('/customer/view/?id_customer=%d', $idCustomer));
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'id_customer' => $idCustomer,
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
    private function updateBillingAddress($idCustomer, $defaultBillingAddress)
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
    private function updateShippingAddress($idCustomer, $defaultShippingAddress)
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
    private function createCustomAddressTransfer($idCustomer, $billingAddress)
    {
        $addressTransfer = $this->createAddressTransfer();

        $addressTransfer->setIdCustomerAddress($billingAddress);
        $addressTransfer->setFkCustomer($idCustomer);

        return $addressTransfer;
    }

}
