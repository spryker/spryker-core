<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthClientTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Oauth\Business\OauthBusinessFactory getFactory()
 * @method \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface getRepository()
 *
 */
class OauthFacade extends AbstractFacade implements OauthFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        return $this->getFactory()->createAccessGrantExecutor()->executeByRequest($oauthRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateAccessToken(OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer): OauthAccessTokenValidationResponseTransfer
    {
        return $this->getFactory()->createAccessTokenReader()->validate($authAccessTokenValidationRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeTransfer $oauthScopeTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer
     */
    public function saveScope(OauthScopeTransfer $oauthScopeTransfer): OauthScopeTransfer
    {
        return $this->getFactory()->createOauthScopeWriter()->save($oauthScopeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return \Generated\Shared\Transfer\OauthClientTransfer
     */
    public function saveClient(OauthClientTransfer $oauthClientTransfer): OauthClientTransfer
    {
        return $this->getFactory()->createOauthClientWriter()->save($oauthClientTransfer);
    }
}
