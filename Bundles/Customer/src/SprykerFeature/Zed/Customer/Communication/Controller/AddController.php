<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use Symfony\Component\HttpFoundation\Request;

class AddController extends AbstractController
{

    public function indexAction()
    {
        /** @var CustomerForm $customerForm */
        $customerForm = $this->getDependencyContainer()->createCustomerForm();
        $customerForm->init();

        $customerForm->handleRequest();

        if ($customerForm->isValid()) {
            $data = $customerForm->getData();
        }

        return $this->viewResponse([
            'form' => $customerForm->createView(),
        ]);
    }
}
