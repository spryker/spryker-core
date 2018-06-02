<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Business\BusinessOnBehalfFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function findActiveCompanyUsersByCustomerIdAction(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer
    {
        return $this->getFacade()->findActiveCompanyUsersByCustomerId($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function setDefaultCompanyUserAction(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $this->getFacade()->setDefaultCompanyUser($companyUserTransfer);
    }
}
