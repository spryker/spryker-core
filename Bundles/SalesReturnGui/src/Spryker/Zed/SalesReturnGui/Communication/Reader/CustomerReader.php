<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Reader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToCustomerFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesFacadeInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        SalesReturnGuiToCustomerFacadeInterface $customerFacade,
        SalesReturnGuiToSalesFacadeInterface $salesFacade
    ) {
        $this->customerFacade = $customerFacade;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerFromReturn(ReturnTransfer $returnTransfer): CustomerTransfer
    {
        $customerReference = $returnTransfer->getCustomerReference();

        if (!$customerReference) {
            return $this->getCustomerFromOrder($returnTransfer);
        }

        $customerTransfer = $this->customerFacade
            ->findCustomerByReference($customerReference)
            ->getCustomerTransfer();

        if (!$customerTransfer) {
            return $this->getCustomerFromOrder($returnTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerFromOrder(ReturnTransfer $returnTransfer): CustomerTransfer
    {
        $idSalesOrder = $returnTransfer->getReturnItems()
            ->getIterator()
            ->current()
            ->getOrderItem()
            ->getFkSalesOrder();

        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);

        return (new CustomerTransfer())
            ->setEmail($orderTransfer->getEmail())
            ->setFirstName($orderTransfer->getFirstName())
            ->setLastName($orderTransfer->getLastName());
    }
}
