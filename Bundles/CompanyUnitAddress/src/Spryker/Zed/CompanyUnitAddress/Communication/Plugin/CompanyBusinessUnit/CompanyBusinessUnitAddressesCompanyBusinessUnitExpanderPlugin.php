<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Communication\Plugin\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 */
class CompanyBusinessUnitAddressesCompanyBusinessUnitExpanderPlugin extends AbstractPlugin implements CompanyBusinessUnitExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the provided company business unit transfer data with company business unit addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function expand(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitTransfer
    {
        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        $companyUnitAddressCollection = $this->getFacade()->getCompanyUnitAddressCollection($companyUnitAddressCriteriaFilterTransfer);
        $companyBusinessUnitTransfer->setAddressCollection($companyUnitAddressCollection);

        return $companyBusinessUnitTransfer;
    }
}
