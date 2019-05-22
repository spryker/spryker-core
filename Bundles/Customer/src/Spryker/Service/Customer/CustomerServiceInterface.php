<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer;

use Generated\Shared\Transfer\AddressTransfer;

interface CustomerServiceInterface
{
    /**
     * Specification:
     * - Generates unique key for address based on address transfer properties without excluded properties list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    public function getUniqueAddressKey(AddressTransfer $addressTransfer): string;

    /**
     * Specification:
     * - Checks if address transfer data is empty.
     * - Checks id customer address and the first name and the last name are not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return bool
     */
    public function isAddressEmpty(?AddressTransfer $addressTransfer = null): bool;
}
