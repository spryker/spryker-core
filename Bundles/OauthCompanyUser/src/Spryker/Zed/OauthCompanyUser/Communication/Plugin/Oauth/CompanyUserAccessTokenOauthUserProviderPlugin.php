<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacade;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUser\Business\League\Grant\CompanyUserAccessTokenGrantType;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 */
class CompanyUserAccessTokenOauthUserProviderPlugin extends AbstractPlugin implements OauthUserProviderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return bool
     */
    public function accept(OauthUserTransfer $oauthUserTransfer): bool
    {
        if ($oauthUserTransfer->getGrantType() !== CompanyUserAccessTokenGrantType::COMPANY_USER_ACCESS_TOKEN_GRANT_TYPE) {
            return false;
        }

        // TODO: i believe that the clientId is irrelevant for UserProvider, the grant type seems to be more strict
        if (!$oauthUserTransfer->getClientId()) {
            return false;
        }

        if ($oauthUserTransfer->getClientId() === $this->getConfig()->getClientId()) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        $oauthUserTransfer->setIsSuccess(false);

        if (!$oauthUserTransfer->getIdCompanyUser()) {
            return $oauthUserTransfer;
        }

        $companyUserFacade = new CompanyUserFacade();
        $companyUserTransfer = $companyUserFacade->getCompanyUserById($oauthUserTransfer->getIdCompanyUser());

        if ($companyUserTransfer === null) {
            return $oauthUserTransfer;
        }

        $companyUserIdentifierTransfer = (new CompanyUserIdentifierTransfer())
            ->setIdCustomer($companyUserTransfer->getCustomer()->getIdCustomer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        // TODO: Need to have a plugin stack
        $companyUserIdentifierTransfer->setExampleProperty($oauthUserTransfer->getExampleProperty());

        $oauthUserTransfer
            ->setUserIdentifier(json_encode($companyUserIdentifierTransfer->toArray()))
        ->setIsSuccess(true);

        return $oauthUserTransfer;
    }
}
