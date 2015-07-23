<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\ReferenceGenerator;

use Generated\Shared\Customer\CustomerInterface;

interface CustomerReferenceGeneratorInterface
{

    /**
     * @param CustomerInterface $orderTransfer
     *
     * @return string
     */
    public function generateCustomerReference(CustomerInterface $orderTransfer);

}
