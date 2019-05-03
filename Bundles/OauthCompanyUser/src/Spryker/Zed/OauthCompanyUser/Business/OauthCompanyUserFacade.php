<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Oauth\Business\OauthFacade;
use Spryker\Zed\OauthCompanyUser\Business\League\Grant\CompanyUserAccessTokenGrantType;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserBusinessFactory getFactory()
 */
class OauthCompanyUserFacade extends AbstractFacade implements OauthCompanyUserFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function createCompanyUserAccessToken(CustomerTransfer $customerTransfer): OauthResponseTransfer
    {
        $customerTransfer
            ->requireCompanyUserTransfer()
            ->getCompanyUserTransfer()
                ->requireIdCompanyUser();

        $oauthFacade = new OauthFacade();

        $request = (new OauthRequestTransfer())
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser())
            ->setGrantType(CompanyUserAccessTokenGrantType::COMPANY_USER_ACCESS_TOKEN_GRANT_TYPE)
            ->setClientId('frontend')    // This should be automatically filled by OAuth module
            ->setClientSecret('abc123'); // This should be automatically filled by OAuth module

        // TODO: need to be solved with generic property using AbstractTransfer
        $request->setExampleProperty($customerTransfer->getExampleProperty());

        $token = $oauthFacade->processAccessTokenRequest($request);

        return $token;
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
    public function getOauthCompanyUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFactory()
            ->createCompanyUserProvider()
            ->getOauthCompanyUser($oauthUserTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
