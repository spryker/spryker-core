<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\RestAddressResponseMapperPluginInterface;

/**
 * @method \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiFactory getFactory()
 */
class CompanyBusinessUnitUuidRestAddressResponseMapperPlugin extends AbstractPlugin implements RestAddressResponseMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `AddressTransfer.companyBusinessUnitUuid` to `RestAddressTransfer.idCompanyBusinessUnitAddress` if exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return \Generated\Shared\Transfer\RestAddressTransfer
     */
    public function map(AddressTransfer $addressTransfer, RestAddressTransfer $restAddressTransfer): RestAddressTransfer
    {
        if ($addressTransfer->getCompanyBusinessUnitAddressUuid()) {
            $restAddressTransfer->setIdCompanyBusinessUnitAddress($addressTransfer->getCompanyBusinessUnitAddressUuidOrFail());
        }

        return $restAddressTransfer;
    }
}
