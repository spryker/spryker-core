<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Plugin\CompanyUsersRestApi;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiFactory getFactory()
 */
class CompanyBusinessUnitMapperPlugin extends AbstractPlugin implements CompanyUsersResourceMapperPluginInterface
{
    /**
     * {@inheritdoc}
     * - Map company business unit to RestCompanyUserAttributesTransfer.
     * - Runs business unit mapper plugin stack.
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
        return $this->getFactory()->createCompanyBusinessUnitMapper()->mapCompanyUserAttributes($companyUserTransfer, $restCompanyUserAttributesTransfer);
    }
}
