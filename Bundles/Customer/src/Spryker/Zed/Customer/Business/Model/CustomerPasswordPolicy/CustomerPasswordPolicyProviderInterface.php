<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

interface CustomerPasswordPolicyProviderInterface
{
    /**
     * @return \Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    public function getPasswordPolicy(): CustomerPasswordPolicyInterface;
}
