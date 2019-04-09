<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitsAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitMapperInterface;

class DefaultBillingAddressMapper implements CompanyBusinessUnitMapperInterface
{
    /**
     * {@inheritdoc}
     * - Maps and replaces defaultBillingAddress id to uuid in the RestCompanyBusinessUnitsAttributesTransfer.
     * - Searches company unit address collection for defaultBillingAddress uuid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitsAttributesTransfer $companyBusinessUnitsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitsAttributesTransfer
     */
    public function map(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        RestCompanyBusinessUnitsAttributesTransfer $companyBusinessUnitsAttributesTransfer
    ): RestCompanyBusinessUnitsAttributesTransfer {
        if (!$companyBusinessUnitTransfer->getDefaultBillingAddress()
            || !$this->hasAddressCollection($companyBusinessUnitTransfer)
        ) {
            return $companyBusinessUnitsAttributesTransfer;
        }

        foreach ($companyBusinessUnitTransfer->getAddressCollection()->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            if ($companyUnitAddressTransfer->getIdCompanyUnitAddress() !== $companyBusinessUnitTransfer->getDefaultBillingAddress()) {
                continue;
            }

            $companyBusinessUnitsAttributesTransfer->setDefaultBillingAddress($companyUnitAddressTransfer->getUuid());

            return $companyBusinessUnitsAttributesTransfer;
        }

        return $companyBusinessUnitsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    protected function hasAddressCollection(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): bool
    {
        return $companyBusinessUnitTransfer->getAddressCollection()
            && $companyBusinessUnitTransfer->getAddressCollection()->getCompanyUnitAddresses()->count() > 0;
    }
}
