<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Plugin\CompanyUsersRestApi;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiFactory getFactory()
 */
class CompanyRoleMapperPlugin extends AbstractPlugin implements CompanyUsersResourceMapperPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands RestCompanyUserAttributesTransfer with company roles.
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
    ): RestCompanyUserAttributesTransfer {
        return $this->getFactory()->createCompanyRoleMapper()->mapRestCompanyUserAttributes($companyUserTransfer, $restCompanyUserAttributesTransfer);
    }
}
