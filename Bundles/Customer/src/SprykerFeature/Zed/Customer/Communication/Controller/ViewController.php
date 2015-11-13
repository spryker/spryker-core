<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Customer\CustomerConfig;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Generated\Shared\Transfer\CustomerTransfer;

/**
 * @method CustomerCommunication getFactory()
 * @method CustomerQueryContainerInterface getQueryContainer()
 * @method CustomerDependencyContainer getDependencyContainer()
 * @method CustomerFacade getFacade()
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
        $idCustomer = $request->get(CustomerConfig::PARAM_ID_CUSTOMER);

        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        $customerTransfer = $this->getFacade()
            ->getCustomer($customerTransfer)
        ;
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
