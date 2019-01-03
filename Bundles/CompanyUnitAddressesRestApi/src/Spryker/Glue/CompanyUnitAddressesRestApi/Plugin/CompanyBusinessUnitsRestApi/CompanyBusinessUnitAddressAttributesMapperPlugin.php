<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi\Plugin\CompanyBusinessUnitsRestApi;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitAttributesMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyUnitAddressesRestApi\CompanyUnitAddressesRestApiFactory getFactory()
 */
class CompanyBusinessUnitAddressAttributesMapperPlugin extends AbstractPlugin implements CompanyBusinessUnitAttributesMapperPluginInterface
{
    /**
     * {@inheritdoc}
     * - Maps company business unit addresses data to RestBusinessUnitAttributesTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    public function mapCompanyBusinessUnitAttributes(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        return $this->getFactory()->createCompanyBusinessUnitAddressAttributesMapper()
            ->mapCompanyBusinessUnitAttributes($companyBusinessUnitTransfer, $restCompanyBusinessUnitAttributesTransfer);
    }
}
