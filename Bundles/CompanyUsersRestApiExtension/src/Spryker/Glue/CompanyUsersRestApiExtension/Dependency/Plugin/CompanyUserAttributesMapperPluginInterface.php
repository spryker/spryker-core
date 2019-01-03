<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;

interface CompanyUserAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps company user data to RestCompanyUserAttributesTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    public function mapCompanyUserAttributes(
        CompanyUserTransfer $companyUserTransfer,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
    ): RestCompanyUserAttributesTransfer;
}
