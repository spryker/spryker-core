<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\HttpFoundation\Request;

class EditController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        /** @var CustomerForm $customerForm */
        $customerForm = $this->getDependencyContainer()->createCustomerForm('update');
        $customerForm->init();

        $customerForm->handleRequest();

        if ($customerForm->isValid()) {
            $data = $customerForm->getData();

            die(dump($data));

            /** @var CustomerTransfer $customer */
            $customer = $this->createCustomerTransfer();
            $customer->fromArray($data, true);

            $this->getFacade()->updateCustomer($customer);
            if ($lastInsertId) {
                $this->redirectResponse(sprintf('/customer/edit?id_customer=%d', $lastInsertId));
            }
        }

        return $this->viewResponse([
            'form' => $customerForm->createView(),
            'id_customer' => $idCustomer,
        ]);
    }

    /**
     * @return CustomerTransfer
     */
    protected function createCustomerTransfer() {
        return new CustomerTransfer();
    }

}
