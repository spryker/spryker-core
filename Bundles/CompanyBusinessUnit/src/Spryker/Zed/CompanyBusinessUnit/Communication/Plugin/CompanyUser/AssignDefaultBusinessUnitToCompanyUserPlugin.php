<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface getFacade()
 */
class AssignDefaultBusinessUnitToCompanyUserPlugin extends AbstractPlugin implements CompanyUserPreSavePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function preSave(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        if ($companyUserTransfer->getIdCompanyUser() === null) {
            $companyUserTransfer->setFkCompanyBusinessUnit(
                $this->getFacade()
                    ->getDefaultBusinessUnitByCompanyId($companyUserTransfer->getFkCompany())
                    ->getIdCompanyBusinessUnit()
            );
        }

        return $companyUserTransfer;
    }
}
