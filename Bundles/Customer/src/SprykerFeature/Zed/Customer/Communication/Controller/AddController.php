<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Class AddController
 * @package SprykerFeature\Zed\Customer\Communication\Controller
 */
class AddController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        /** @var CustomerForm $customerForm */
        $customerForm = $this->getDependencyContainer()->createCustomerForm('add');
        $customerForm->init();

        $customerForm->handleRequest();

        if ($customerForm->isValid()) {
            $data = $customerForm->getData();

            /** @var CustomerTransfer $customer */
            $customer = $this->createCustomerTransfer();
            $customer->fromArray($data, true);

            $lastInsertId = $this->getFacade()->registerCustomer($customer);

            return $this->redirectResponse('/customer/');
        }

        return $this->viewResponse([
            'form' => $customerForm->createView(),
        ]);
    }

    /**
     * @return CustomerTransfer
     */
    protected function createCustomerTransfer() {
        return new CustomerTransfer();
    }
}
