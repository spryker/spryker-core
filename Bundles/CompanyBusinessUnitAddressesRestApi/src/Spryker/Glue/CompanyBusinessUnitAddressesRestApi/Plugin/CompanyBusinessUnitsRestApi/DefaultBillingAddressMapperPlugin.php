<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\CompanyBusinessUnitsRestApi;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiFactory getFactory()
 */
class DefaultBillingAddressMapperPlugin extends AbstractPlugin implements CompanyBusinessUnitMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps and replaces defaultBillingAddress id to uuid in the RestCompanyBusinessUnitAttributesTransfer.
     * - Searches company unit address collection for defaultBillingAddress uuid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    public function mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        return $this->getFactory()
            ->createCompanyBusinessUnitAddressMapper()
            ->mapDefaultBillingAddressIdFromCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
                $companyBusinessUnitTransfer,
                $restCompanyBusinessUnitAttributesTransfer
            );
    }
}
