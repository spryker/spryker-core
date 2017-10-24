<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Orm\Zed\Customer\Persistence\SpyCustomer;

interface EmailValidatorInterface
{
    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return bool
     */
    public function isFormatValid(SpyCustomer $customerEntity);

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return bool
     */
    public function isEmailAvailableForCustomer(SpyCustomer $customerEntity);
}
