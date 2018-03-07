<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\Company;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitCreatePlugin extends AbstractPlugin implements CompanyPostCreatePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function postCreate(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        return $this->createCompanyBusinessUnit($companyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function createCompanyBusinessUnit(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        $companyTransfer->requireIdCompany();

        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setFkCompany($companyTransfer->getIdCompany())
            ->setName($this->getConfig()->getCompanyBusinessUnitDefaultName());

        $companyBusinessUnitResponseTransfer = $this->getFacade()->create($companyBusinessUnitTransfer);
        if ($companyTransfer->getInitialUserTransfer() !== null) {
            $companyTransfer->getInitialUserTransfer()
                ->setFkCompanyBusinessUnit(
                    $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()
                        ->getIdCompanyBusinessUnit()
                );
        }

        return $companyTransfer;
    }
}
