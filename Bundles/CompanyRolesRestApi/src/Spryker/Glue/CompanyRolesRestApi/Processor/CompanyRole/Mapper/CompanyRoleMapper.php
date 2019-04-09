<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\RestCompanyRolesAttributesTransfer;

class CompanyRoleMapper implements CompanyRoleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     * @param \Generated\Shared\Transfer\RestCompanyRolesAttributesTransfer $restCompanyRolesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyRolesAttributesTransfer
     */
    public function mapCompanyRoleTransferToRestCompanyRolesAttributesTransfer(
        CompanyRoleTransfer $companyRoleTransfer,
        RestCompanyRolesAttributesTransfer $restCompanyRolesAttributesTransfer
    ): RestCompanyRolesAttributesTransfer {
        return $restCompanyRolesAttributesTransfer->fromArray($companyRoleTransfer->toArray(), true);
    }
}
