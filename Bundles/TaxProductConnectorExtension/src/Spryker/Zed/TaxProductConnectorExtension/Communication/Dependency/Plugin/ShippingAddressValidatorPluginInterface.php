<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnectorExtension\Communication\Dependency\Plugin;

use Generated\Shared\Transfer\AddressTransfer;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 *
 * Implement this plugin if you want to validate if shipping address is applicable for tax calculating.
 */
interface ShippingAddressValidatorPluginInterface
{
    /**
     * Specification:
     * - Checks if provided `AddressTransfer` is applicable for calculating product items tax rate.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function isValid(AddressTransfer $addressTransfer): bool;
}
