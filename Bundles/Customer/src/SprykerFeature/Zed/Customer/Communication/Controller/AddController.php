<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use Generated\Shared\Transfer\CustomerTransfer;

class AddController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        /** @var CustomerForm $customerForm */
        $form = $this->getDependencyContainer()
                     ->createCustomerForm('add')
        ;
        $form->init();

        $form->handleRequest();

        if (true === $form->isValid()) {
            $data = $form->getData();

            $customerTransfer = $this->createCustomerTransfer();
            $customerTransfer->fromArray($data, true);

            $lastInsertId = $this->getFacade()
                                 ->registerCustomer($customerTransfer)
            ;

            return $this->redirectResponse('/customer/');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

}
