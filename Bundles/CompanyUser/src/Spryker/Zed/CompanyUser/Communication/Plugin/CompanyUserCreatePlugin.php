<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Communication\Plugin;

use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\Company\Dependency\Plugin\CompanyPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface getFacade()
 */
class CompanyUserCreatePlugin extends AbstractPlugin implements CompanyPostCreatePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function postCreate(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        return $this->createCompanyUser($companyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function createCompanyUser(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        $companyTransfer->requireInitialUserTransfer();
        $companyUserTransfer = $companyTransfer->getInitialUserTransfer();
        $companyUserTransfer->setFkCompany($companyTransfer->getIdCompany());
        $companyUserResponseTransfer = $this->getFacade()->create($companyUserTransfer);
        $companyTransfer->setInitialUserTransfer($companyUserResponseTransfer->getCompanyUserTransfer());

        return $companyTransfer;
    }
}
