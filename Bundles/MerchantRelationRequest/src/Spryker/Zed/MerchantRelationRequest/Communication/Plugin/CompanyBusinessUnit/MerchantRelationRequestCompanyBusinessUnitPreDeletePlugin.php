<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Communication\Plugin\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPreDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationRequest\Communication\MerchantRelationRequestCommunicationFactory getFactory()
 */
class MerchantRelationRequestCompanyBusinessUnitPreDeletePlugin extends AbstractPlugin implements CompanyBusinessUnitPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CompanyBusinessUnitTransfer.idCompanyBusinessUnit` to be set.
     * - Deletes merchant relation request entities and related merchant relation request to company business unit entities
     * for requests where provided company business unit is an owner.
     * - Deletes assigned merchant relation request to company business unit entities related to provided company business unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function preDelete(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): void
    {
        $this->getFacade()->deleteCompanyBusinessUnitMerchantRelationRequests($companyBusinessUnitTransfer);
    }
}
