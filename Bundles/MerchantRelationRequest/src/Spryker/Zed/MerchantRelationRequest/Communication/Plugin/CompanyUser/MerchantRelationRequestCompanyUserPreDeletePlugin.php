<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationRequest\Communication\MerchantRelationRequestCommunicationFactory getFactory()
 */
class MerchantRelationRequestCompanyUserPreDeletePlugin extends AbstractPlugin implements CompanyUserPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CompanyUserTransfer.idCompanyUser` to be set.
     * - Deletes merchant relation request entities related to provided merchant user transfer.
     * - Deletes merchant relation request to company business unit entities related to deleted merchant relation requests.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function preDelete(CompanyUserTransfer $companyUserTransfer): void
    {
        $this->getFacade()->deleteCompanyUserMerchantRelationRequests($companyUserTransfer);
    }
}
