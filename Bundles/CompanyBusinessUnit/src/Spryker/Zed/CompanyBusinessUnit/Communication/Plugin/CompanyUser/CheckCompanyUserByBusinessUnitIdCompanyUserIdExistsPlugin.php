<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreSaveCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CheckCompanyUserByBusinessUnitIdCompanyUserIdExistsPlugin extends AbstractPlugin implements CompanyUserPreSaveCheckPluginInterface
{
    protected const MESSAGE_ERROR_COMPANY_USER_ALREADY_ATTACHED = 'Company user already attached to this business unit.';

    /**
     * Specification:
     * - Checks exists relation between customer and business unit before save company user for avoid duplicates of company user entity
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function check(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $existsCompanyUser = $this->getFacade()
            ->checkCompanyUserByBusinessUnitIdCompanyUserIdExists($companyUserResponseTransfer->getCompanyUser());

        if ($existsCompanyUser) {
            $message = (new ResponseMessageTransfer())
                ->setText(static::MESSAGE_ERROR_COMPANY_USER_ALREADY_ATTACHED);

            $companyUserResponseTransfer->setIsSuccessful(false)
                ->addMessage($message);
        }

        return $companyUserResponseTransfer;
    }
}
