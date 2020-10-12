<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerPasswordPolicyValidatorInterface
{

    /**
     * @param Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param array $config
     *
     * @return Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function checkLength(CustomerTransfer $customerTransfer, CustomerPasswordPolicyResultTransfer $resultTransfer, array $config): CustomerPasswordPolicyResultTransfer;

    /**
     * @param Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param array $config
     *
     * @return Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function checkCharset(CustomerTransfer $customerTransfer, CustomerPasswordPolicyResultTransfer $resultTransfer, array $config): CustomerPasswordPolicyResultTransfer;

    /**
     * @param Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param int $sequenceLimit
     *
     * @return Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function checkSequence(CustomerTransfer $customerTransfer, CustomerPasswordPolicyResultTransfer $resultTransfer, array $sequenceLimit): CustomerPasswordPolicyResultTransfer;

    /**
     * @param Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param array $config
     *
     * @return Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function checkBlacklist(CustomerTransfer $customerTransfer, CustomerPasswordPolicyResultTransfer $resultTransfer, array $blacklist): CustomerPasswordPolicyResultTransfer;
}
