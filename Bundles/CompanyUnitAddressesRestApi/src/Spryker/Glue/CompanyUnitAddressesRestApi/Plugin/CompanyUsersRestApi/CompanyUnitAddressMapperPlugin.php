<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi\Plugin\CompanyUsersRestApi;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyUnitAddressesRestApi\CompanyUnitAddressesRestApiFactory getFactory()
 */
class CompanyUnitAddressMapperPlugin extends AbstractPlugin implements CompanyUsersResourceMapperPluginInterface
{
    /**
     * {@inheritdoc}
     * - Maps RestCompanyUserAttributesTransfer with company unit addresses.
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
        return $this->getFactory()->createCompanyUnitAddressExpander()->expand($companyUserTransfer, $restCompanyUserAttributesTransfer);
    }
}
