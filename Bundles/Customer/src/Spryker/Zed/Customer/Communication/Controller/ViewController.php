<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Communication\CustomerCommunicationFactory;
use Spryker\Zed\Customer\CustomerConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerFacade getFacade()
 * @method CustomerCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{

    /**
     * @param Request $request
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
     * @return CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

}
