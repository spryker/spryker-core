<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;

class CompanyBusinessUnitMapper implements CompanyBusinessUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    public function mapCompanyUserAttributes(
        CompanyUserTransfer $companyUserTransfer,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
    ): RestCompanyUserAttributesTransfer {
        $restCompanyUserAttributesTransfer->setCompanyBusinessUnit(
            (new RestCompanyBusinessUnitAttributesTransfer())
                ->fromArray($companyUserTransfer->toArray(), true)
        );
        $restCompanyUserAttributesTransfer = $this->executeCompanyBusinessUnitMapperPlugins($companyUserTransfer, $restCompanyUserAttributesTransfer);

        return $restCompanyUserAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    protected function executeCompanyBusinessUnitMapperPlugins(
        CompanyUserTransfer $companyUserTransfer,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
    ): RestCompanyUserAttributesTransfer {
        //TODO: inject and run plugins here.

        return $restCompanyUserAttributesTransfer;
    }
}
