<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

interface EmailValidatorInterface
{
    /**
     * @param string $email
     *
     * @return bool
     */
    public function isFormatValid($email);

    /**
     * @param string $email
     * @param int $idCustomer
     *
     * @return bool
     */
    public function isEmailAvailableForCustomer($email, $idCustomer);

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailLengthValid(string $email): bool;
}
