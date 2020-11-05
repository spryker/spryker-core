<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestAddressTransfer;

/**
 * Plugin interface is used to validate address source.
 *
 * Runs during `/checkout-data` and `/checkout` requests.
 * Should be used to only validate the data is provided.
 */
interface AddressSourceProviderPluginInterface
{
    /**
     * Specification:
     * - Checks if the address id is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    public function isAddressSourceProvided(RestAddressTransfer $restAddressTransfer): bool;
}
