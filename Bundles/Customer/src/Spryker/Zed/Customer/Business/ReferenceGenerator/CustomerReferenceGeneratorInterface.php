<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Business\ReferenceGenerator;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerReferenceGeneratorInterface
{

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $orderTransfer
     *
     * @return string
     */
    public function generateCustomerReference(CustomerTransfer $orderTransfer);

}
