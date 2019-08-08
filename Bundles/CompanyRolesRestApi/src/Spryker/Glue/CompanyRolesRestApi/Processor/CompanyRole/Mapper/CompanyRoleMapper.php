<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;

class CompanyRoleMapper implements CompanyRoleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     * @param \Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer
     */
    public function mapCompanyRoleTransferToRestCompanyRoleAttributesTransfer(
        CompanyRoleTransfer $companyRoleTransfer,
        RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer
    ): RestCompanyRoleAttributesTransfer {
        return $restCompanyRoleAttributesTransfer->fromArray($companyRoleTransfer->toArray(), true);
    }
}
