<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Communication\Plugin\MerchantRelationRequest;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CompanyUnitAddress\Communication\CompanyUnitAddressCommunicationFactory getFactory()
 */
class AssigneeCompanyBusinessUnitAddressMerchantRelationRequestExpanderPlugin extends AbstractPlugin implements MerchantRelationRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantRelationRequestCollectionTransfer.merchantRelationRequest.assigneeCompanyBusinessUnits.companyBusinessUnitTransfer` to be set.
     * - Expands `MerchantRelationRequestCollectionTransfer.merchantRelationRequest.assigneeCompanyBusinessUnits` with
     *  the corresponding company business unit addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expand(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        return $this->getFacade()
            ->expandMerchantRelationRequestCollectionWithAssigneeCompanyBusinessUnitAddress(
                $merchantRelationRequestCollectionTransfer,
            );
    }
}
