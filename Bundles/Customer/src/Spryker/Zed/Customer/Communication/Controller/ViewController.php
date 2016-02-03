<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get(CustomerConstants::PARAM_ID_CUSTOMER);

        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        $customerTransfer = $this->getFacade()
            ->getCustomer($customerTransfer);
        $addresses = $customerTransfer->getAddresses()->toArray();
        $customerArray = $customerTransfer->toArray();

        if ($addresses[AddressesTransfer::ADDRESSES] instanceof \ArrayObject && $addresses[AddressesTransfer::ADDRESSES]->count() < 1) {
            $addresses = [];
        }

        return $this->viewResponse([
            'customer' => $customerArray,
            'addresses' => $addresses,
            'idCustomer' => $idCustomer,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

}
