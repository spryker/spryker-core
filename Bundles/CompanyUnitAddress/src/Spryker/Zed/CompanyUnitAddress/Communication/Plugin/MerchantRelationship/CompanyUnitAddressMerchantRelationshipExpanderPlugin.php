<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Communication\Plugin\MerchantRelationship;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CompanyUnitAddress\Communication\CompanyUnitAddressCommunicationFactory getFactory()
 */
class CompanyUnitAddressMerchantRelationshipExpanderPlugin extends AbstractPlugin implements MerchantRelationshipExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantRelationshipTransfer.assigneeCompanyBusinessUnits` to be set.
     * - Requires `MerchantRelationshipTransfer.assigneeCompanyBusinessUnits.companyBusinessUnits.idCompanyBusinessUnit` to be set.
     * - Expands `MerchantRelationshipTransfer.assigneeCompanyBusinessUnits` with the corresponding company business unit addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function expand(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        return $this->getFacade()
            ->expandMerchantRelationshipCollectionWithCompanyUnitAddress($merchantRelationshipCollectionTransfer)
            ->getMerchantRelationships()
            ->getIterator()
            ->current();
    }
}
