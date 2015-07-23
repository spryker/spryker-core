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

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        /** @var CustomerTransfer $customer */
        $customer = $this->getFacade()
            ->getCustomer($customerTransfer)
        ;
        $addresses = $customer->getAddresses();

        return $this->viewResponse([
            'customer' => $customer->toArray(),
            'addresses' => $addresses->toArray(),
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

}
