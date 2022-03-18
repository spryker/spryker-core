<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidation\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface SessionCustomerValidationToCustomerClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer(): ?CustomerTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByEmail(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * @return void
     */
    public function logout(): void;
}
