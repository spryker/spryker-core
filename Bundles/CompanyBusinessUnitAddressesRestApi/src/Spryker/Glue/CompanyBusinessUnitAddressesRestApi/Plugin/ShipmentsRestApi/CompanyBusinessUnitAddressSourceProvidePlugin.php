<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\RestAddressTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceProvidePluginInterface;

/**
 * @method \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiFactory getFactory()
 */
class CompanyBusinessUnitAddressSourceProvidePlugin extends AbstractPlugin implements AddressSourceProvidePluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks is company business unit address ID is provided in the address attributes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    public function isAddressSourceProvided(RestAddressTransfer $restAddressTransfer): bool
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitAddressIdValidator()
            ->validateCompanyBusinessUnitAddressIdProvided($restAddressTransfer);
    }
}
