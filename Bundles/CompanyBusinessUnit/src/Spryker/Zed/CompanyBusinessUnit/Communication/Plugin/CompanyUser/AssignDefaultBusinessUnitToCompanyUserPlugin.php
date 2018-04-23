<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
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
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function preSave(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        if ($companyUserResponseTransfer->getCompanyUser()->getIdCompanyUser() === null
            && $companyUserResponseTransfer->getCompanyUser()->getFkCompanyBusinessUnit() === null) {
            $companyBusinessUnit = $this->getFacade()
                ->findDefaultBusinessUnitByCompanyId($companyUserResponseTransfer->getCompanyUser()->getFkCompany());

            if ($companyBusinessUnit !== null) {
                $companyUserResponseTransfer->getCompanyUser()->setFkCompanyBusinessUnit(
                    $companyBusinessUnit->getIdCompanyBusinessUnit()
                );
            }
        }

        return $companyUserResponseTransfer;
    }
}
