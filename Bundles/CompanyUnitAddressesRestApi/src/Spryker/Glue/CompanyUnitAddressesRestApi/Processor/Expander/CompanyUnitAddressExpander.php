<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi\Processor\Expander;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;

class CompanyUnitAddressExpander implements CompanyUnitAddressExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    public function expand(
        CompanyUserTransfer $companyUserTransfer,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
    ): RestCompanyUserAttributesTransfer {
        foreach ($companyUserTransfer->getCompanyBusinessUnit()->getAddressCollection()->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $restCompanyUserAttributesTransfer->getCompanyBusinessUnit()->addAddress(
                (new RestCompanyUnitAddressAttributesTransfer())->fromArray($companyUnitAddressTransfer->toArray(), true)
            );
        }

        return $restCompanyUserAttributesTransfer;
    }
}
