<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use Generated\Shared\Transfer\SpyOauthAccessTokenEntityTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;

/**
 * @method \Spryker\Zed\Oauth\Persistence\OauthPersistenceFactory getFactory()
 */
interface OauthEntityManagerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyOauthAccessTokenEntityTransfer $spyOauthAccessTokenEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface|\Generated\Shared\Transfer\SpyOauthAccessTokenEntityTransfer
     */
    public function saveAccessToken(SpyOauthAccessTokenEntityTransfer $spyOauthAccessTokenEntityTransfer): SpyOauthAccessTokenEntityTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyOauthClientEntityTransfer $spyOauthClientEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface|\Generated\Shared\Transfer\SpyOauthClientEntityTransfer
     */
    public function saveClient(SpyOauthClientEntityTransfer $spyOauthClientEntityTransfer): SpyOauthClientEntityTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyOauthScopeEntityTransfer $spyOauthScopeEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface|\Generated\Shared\Transfer\SpyOauthScopeEntityTransfer
     */
    public function saveScope(SpyOauthScopeEntityTransfer $spyOauthScopeEntityTransfer): SpyOauthScopeEntityTransfer;

    /**
     * @api
     *
     * @param string $identifier
     *
     * @return void
     */
    public function deleteAccessTokenByIdentifier(string $identifier): void;
}
