<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\Hydrator;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Customer\Business\Customer\CustomerInterface;

class OfferCustomerHydrator implements OfferCustomerHydratorInterface
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
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrate(OfferTransfer $offerTransfer): OfferTransfer
    {
        $customerTransfer = $this->customer->findByReference($offerTransfer->getCustomerReference());
        $offerTransfer->setCustomer($customerTransfer);

        return $offerTransfer;
    }
}
