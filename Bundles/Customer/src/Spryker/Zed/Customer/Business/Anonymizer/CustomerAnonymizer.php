<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Anonymizer;


use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\Base\SpyCustomer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;

class CustomerAnonymizer implements CustomerAnonymizerInterface
{
    public function process(CustomerTransfer $customerTransfer)
    {
        $customerTransfer->setAnonymizedAt(new \DateTime());
        $customerTransfer->setEmail(md5($customerTransfer->getEmail()));

        $customerTransfer->setFirstName(null);
        $customerTransfer->setLastName(null);
        $customerTransfer->setSalutation(null);
        $customerTransfer->setGender(null);
        $customerTransfer->setDateOfBirth(null);

        return $customerTransfer;
    }
}