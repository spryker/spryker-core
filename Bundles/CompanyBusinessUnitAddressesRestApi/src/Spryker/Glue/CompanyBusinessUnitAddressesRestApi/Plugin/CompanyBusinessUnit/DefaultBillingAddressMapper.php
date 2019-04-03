<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitMapperInterface;

class DefaultBillingAddressMapper implements CompanyBusinessUnitMapperInterface
{
    /**
     * {@inheritDoc}
     * - Maps and replaces defaultBillingAddress id to uuid in the RestCompanyBusinessUnitAttributesTransfer.
     * - Searches company unit address collection for defaultBillingAddress uuid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $companyBusinessUnitAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    public function map(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        RestCompanyBusinessUnitAttributesTransfer $companyBusinessUnitAttributesTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        if (!$companyBusinessUnitTransfer->getDefaultBillingAddress()
            || !$companyBusinessUnitTransfer->getAddressCollection()->getCompanyUnitAddresses()->count()
        ) {
            return $companyBusinessUnitAttributesTransfer;
        }

        foreach ($companyBusinessUnitTransfer->getAddressCollection()->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            if ($companyUnitAddressTransfer->getIdCompanyUnitAddress() !== $companyBusinessUnitTransfer->getDefaultBillingAddress()) {
                continue;
            }

            $companyBusinessUnitAttributesTransfer->setDefaultBillingAddress($companyUnitAddressTransfer->getUuid());

            return $companyBusinessUnitAttributesTransfer;
        }

        return $companyBusinessUnitAttributesTransfer;
    }
}
