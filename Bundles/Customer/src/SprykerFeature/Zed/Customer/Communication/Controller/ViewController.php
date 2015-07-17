<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Generated\Shared\Transfer\CustomerTransfer;

class ViewController extends AbstractController
{
    public function indexAction(Request $request) {
        $idCustomer = $request->get('id_customer');

        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        $customer = $this->getFacade()->getCustomer($customerTransfer);

        return $this->viewResponse([
            'customer' => $customer->toArray(),
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
