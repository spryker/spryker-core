<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Finder;

use Generated\Shared\Transfer\OauthScopeFindTransfer;

interface StorefrontScopeFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @throws \Spryker\Glue\GlueStorefrontApiApplication\Exception\CacheFileNotFoundException
     *
     * @return string|null
     */
    public function findScope(OauthScopeFindTransfer $oauthScopeFindTransfer): ?string;
}
