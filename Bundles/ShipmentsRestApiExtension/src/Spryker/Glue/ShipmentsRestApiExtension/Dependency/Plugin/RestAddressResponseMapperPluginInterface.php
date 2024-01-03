<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;

/**
 * Plugin interface is used to map `AddressTransfer` to `RestAddressTransfer`.
 *
 * Runs during `/checkout-data` and `/checkout` requests.
 */
interface RestAddressResponseMapperPluginInterface
{
    /**
     * Specification:
     * - Maps `AddressTransfer` to `RestAddressTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return \Generated\Shared\Transfer\RestAddressTransfer
     */
    public function map(AddressTransfer $addressTransfer, RestAddressTransfer $restAddressTransfer): RestAddressTransfer;
}
