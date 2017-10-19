<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Customer\Business\Customer\CustomerInterface;

class CustomerOrderHydrator implements CustomerOrderHydratorInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\Customer\CustomerInterface
     */
    protected $customer;

    /**
     * @param \Spryker\Zed\Customer\Business\Customer\CustomerInterface $customer
     */
    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderTransfer(OrderTransfer $orderTransfer)
    {
        if ($orderTransfer->getCustomer()) {
            return $orderTransfer;
        }

        if (!$orderTransfer->getCustomerReference()) {
            return $orderTransfer;
        }

        $customerTransfer = $this->customer->findByReference($orderTransfer->getCustomerReference());

        if ($customerTransfer) {
            $orderTransfer->setCustomer($customerTransfer);
        }

        return $orderTransfer;
    }
}
