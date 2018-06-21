<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;

/**
 * @method \Spryker\Zed\Oauth\Business\OauthBusinessFactory getFactory()
 */
interface OauthFacadeInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer;

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateAccessToken(OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer): OauthAccessTokenValidationResponseTransfer;

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\SpyOauthScopeEntityTransfer $spyOauthScopeEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyOauthScopeEntityTransfer
     */
    public function saveScope(SpyOauthScopeEntityTransfer $spyOauthScopeEntityTransfer): SpyOauthScopeEntityTransfer;

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\SpyOauthClientEntityTransfer $spyOauthClientEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyOauthClientEntityTransfer
     */
    public function saveClient(SpyOauthClientEntityTransfer $spyOauthClientEntityTransfer): SpyOauthClientEntityTransfer;
}
