<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use Symfony\Component\HttpFoundation\Request;

class EditController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        /** @var CustomerForm $form */
        $form = $this->getDependencyContainer()->createCustomerForm('update');
        $form->init();

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            /** @var CustomerTransfer $customer */
            $customer = $this->createCustomerTransfer();
            $customer->fromArray($data, true);
            $this->getFacade()->updateCustomer($customer);

            $defaultBillingAddress = !empty($data['default_billing_address']) ? $data['default_billing_address'] : false;
            if (false === empty($defaultBillingAddress)) {
                $this->updateBillingAddress($idCustomer, $defaultBillingAddress);
            }

            $defaultShippingAddress = !empty($data['default_shipping_address']) ? $data['default_shipping_address'] : false;
            if (false === empty($defaultShippingAddress)) {
                $this->updateShippingAddress($idCustomer, $defaultShippingAddress);
            }

            return $this->redirectResponse(sprintf('/customer/view/?id_customer=%d', $idCustomer));
        }

        return $this->viewResponse([
            'form'        => $form->createView(),
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
     * @return CustomerAddressTransfer
     */
    protected function createCustomerAddressTransfer()
    {
        return new CustomerAddressTransfer();
    }

    /**
     * @param int $idCustomer
     * @param int $defaultBillingAddress
     */
    private function updateShippingAddress($idCustomer, $defaultBillingAddress)
    {
        $addressTransfer = $this->createCustomAddressTransfer($idCustomer, $defaultBillingAddress);
        $this->getFacade()->setDefaultBillingAddress($addressTransfer);
    }

    /**
     * @param int $idCustomer
     * @param int $defaultShippingAddress
     */
    private function updateBillingAddress($idCustomer, $defaultShippingAddress)
    {
        $addressTransfer = $this->createCustomAddressTransfer($idCustomer, $defaultShippingAddress);
        $this->getFacade()->setDefaultShippingAddress($addressTransfer);
    }

    /**
     * @param int $idCustomer
     * @param int $billingAddress
     *
     * @return CustomerAddressTransfer
     */
    private function createCustomAddressTransfer($idCustomer, $billingAddress)
    {
        $addressTransfer = $this->createCustomerAddressTransfer();

        $addressTransfer->setIdCustomerAddress($billingAddress);
        $addressTransfer->setFkCustomer($idCustomer);

        return $addressTransfer;
    }

}
