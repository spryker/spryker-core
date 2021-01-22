<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\RestAddressTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceCheckerPluginInterface;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class CustomerAddressSourceCheckerPlugin extends AbstractPlugin implements AddressSourceCheckerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks is customer address ID is provided in the address attributes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    public function isAddressSourceProvided(RestAddressTransfer $restAddressTransfer): bool
    {
        return $restAddressTransfer->getId() !== null;
    }
}
