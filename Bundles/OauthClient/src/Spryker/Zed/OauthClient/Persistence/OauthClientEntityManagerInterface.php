<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Persistence;

use Generated\Shared\Transfer\AccessTokenCacheTransfer;

interface OauthClientEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AccessTokenCacheTransfer $accessTokenCacheTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenCacheTransfer
     */
    public function saveAccessTokenCache(
        AccessTokenCacheTransfer $accessTokenCacheTransfer
    ): AccessTokenCacheTransfer;

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    public function deleteAccessTokenCacheEntityByCacheKey(string $cacheKey): void;
}
