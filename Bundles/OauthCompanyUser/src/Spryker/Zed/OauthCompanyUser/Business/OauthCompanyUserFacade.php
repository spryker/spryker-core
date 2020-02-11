<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business;

use Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserBusinessFactory getFactory()
 */
class OauthCompanyUserFacade extends AbstractFacade implements OauthCompanyUserFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function createCompanyUserAccessToken(CustomerTransfer $customerTransfer): OauthResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyUserAccessTokenCreator()
            ->createAccessToken($customerTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessToken(CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer): CustomerResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyUserAccessTokenReader()
            ->getCustomerByAccessToken($companyUserAccessTokenRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthCompanyUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFactory()
            ->createCompanyUserProvider()
            ->getOauthCompanyUser($oauthUserTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        return $this->getFactory()
            ->createScopeProvider()
            ->getScopes($oauthScopeRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function installCompanyUserOauthData(): void
    {
        $this->getFactory()->createOauthScopeInstaller()->install();
    }
}
