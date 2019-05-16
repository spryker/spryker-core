<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Communication\Plugin\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUser\CompanyUserConfig getConfig()
 */
class CompanyUserReloadCustomerTransferExpanderPlugin extends AbstractPlugin implements CustomerTransferExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Reloads company user if it is already set in Customer transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandTransfer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if (!$customerTransfer->getCompanyUserTransfer() || !$customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()) {
            return $customerTransfer;
        }

        $idCompanyUser = $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser();
        $companyUserTransfer = $this->getFacade()->getCompanyUserById($idCompanyUser);

        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        return $customerTransfer;
    }
}
