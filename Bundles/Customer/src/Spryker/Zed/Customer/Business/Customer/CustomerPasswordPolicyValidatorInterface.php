<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerResponseTransfer;

interface CustomerPasswordPolicyValidatorInterface
{
    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param int[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validateLength(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer,
        array $config
    ): CustomerResponseTransfer;

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string[][] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validateCharset(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer,
        array $config
    ): CustomerResponseTransfer;

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $resultTransfer
     * @param int[] $sequenceLimit
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validateSequence(
        string $password,
        CustomerResponseTransfer $resultTransfer,
        array $sequenceLimit
    ): CustomerResponseTransfer;

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $resultTransfer
     * @param string[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validateBlacklist(
        string $password,
        CustomerResponseTransfer $resultTransfer,
        array $config
    ): CustomerResponseTransfer;
}
