<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessTokenAction(CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer): CustomerResponseTransfer
    {
        return $this->getFacade()->getCustomerByAccessToken($companyUserAccessTokenRequestTransfer);
    }
}
