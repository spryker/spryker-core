<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication\Plugin;

use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;
use Spryker\Zed\CompanyUnitAddressExtension\Communication\Plugin\CompanyUnitAddressEntityTransferHydratorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Communication\CompanyUnitAddressLabelCommunicationFactory getFactory()
 */
class CompanyUnitAddressEntityTransferHydratorPlugin extends AbstractPlugin implements CompanyUnitAddressEntityTransferHydratorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $addressEntityTransfer
     *
     * @return void
     */
    public function hydrate(SpyCompanyUnitAddressEntityTransfer $addressEntityTransfer)
    {
        $labelToAddressRelations = $this->getRepository()
            ->findCompanyUnitAddressLabelToCompanyUnitAddressRelations(
                $addressEntityTransfer->getIdCompanyUnitAddress()
            );

        $addressEntityTransfer->setSpyCompanyUnitAddressLabelToCompanyUnitAddresses($labelToAddressRelations);
    }
}
