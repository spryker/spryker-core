<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Customer\Strategy;

use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerDataChangeResponseTransfer;

interface ConfirmCustomerDataChangeRequestStrategyInterface
{
    /**
     * Specification:
     * - Checks if the strategy is applicable for the customer data change request.
     *
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): bool;

    /**
     * Specification:
     * - Executes the strategy to confirm customer data change request.
     *
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeResponseTransfer
     */
    public function execute(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerDataChangeResponseTransfer;
}
