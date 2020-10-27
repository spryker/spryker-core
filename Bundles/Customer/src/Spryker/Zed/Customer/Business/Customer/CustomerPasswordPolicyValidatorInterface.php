<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;

interface CustomerPasswordPolicyValidatorInterface
{
    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param int[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function validateLength(
        string $password,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer;

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param string[][] $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function validateCharset(
        string $password,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer;

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param int[] $sequenceLimit
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function validateSequence(
        string $password,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $sequenceLimit
    ): CustomerPasswordPolicyResultTransfer;

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param string[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function validateBlacklist(
        string $password,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer;
}
