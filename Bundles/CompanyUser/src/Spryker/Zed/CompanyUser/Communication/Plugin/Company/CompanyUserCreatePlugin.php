<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Communication\Plugin\Company;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface getFacade()
 */
class CompanyUserCreatePlugin extends AbstractPlugin implements CompanyPostCreatePluginInterface
{
    /**
     * Specification:
     * - Plugin is used for creating initial admin user during registration company in Yves.
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function postCreate(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        if ($companyResponseTransfer->getCompanyTransfer()->getInitialUserTransfer() !== null) {
            return $this->getFacade()->createInitialCompanyUser($companyResponseTransfer);
        }

        return $companyResponseTransfer;
    }
}
