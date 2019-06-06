<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Session;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerSessionInterface
{
    /**
     * @return void
     */
    public function logout();

    /**
     * @return bool
     */
    public function hasCustomer();

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer();

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomerRawData(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerRawData(): ?CustomerTransfer;

    /**
     * @return void
     */
    public function markCustomerAsDirty();
}
