<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUser\Business\League\Grant\CompanyUserAccessTokenGrantType;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Communication\OauthCompanyUserCommunicationFactory getFactory()
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 */
class CompanyUserAccessTokenOauthUserProviderPlugin extends AbstractPlugin implements OauthUserProviderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns true if CompanyUser GrantType is provided, false otherwise.
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

        return true;
    }

    /**
     * {@inheritdoc}
     * - Retrieves active company user if idCompanyUser provided.
     * - Expands the OauthUserTransfer if active company user exists.
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

        $companyUserTransfer = $this->findActiveCompanyUser((int)$oauthUserTransfer->getIdCompanyUser());

        if (!$companyUserTransfer) {
            return $oauthUserTransfer;
        }

        $companyUserIdentifierTransfer = (new CompanyUserIdentifierTransfer())
            ->fromArray($oauthUserTransfer->toArray(), true)
            ->setIdCustomer($companyUserTransfer->getCustomer()->getIdCustomer())
            ->setIdCompanyUser((string)$companyUserTransfer->getIdCompanyUser());

        $encodedPayload = $this->getFactory()
            ->getUtilEncodingService()
            ->encodeJson($companyUserIdentifierTransfer->toArray());

        $oauthUserTransfer
            ->setUserIdentifier($encodedPayload)
            ->setIsSuccess(true);

        return $oauthUserTransfer;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findActiveCompanyUser(int $idCompanyUser): ?CompanyUserTransfer
    {
        $activeCompanyUsersTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->findActiveCompanyUsersByIds([$idCompanyUser]);

        return array_shift($activeCompanyUsersTransfer);
    }
}
