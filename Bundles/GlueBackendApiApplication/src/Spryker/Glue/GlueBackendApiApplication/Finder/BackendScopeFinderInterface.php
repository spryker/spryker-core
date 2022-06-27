<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Finder;

use Generated\Shared\Transfer\OauthScopeFindTransfer;

interface BackendScopeFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @throws \Spryker\Glue\GlueBackendApiApplication\Exception\CacheFileNotFoundException
     *
     * @return string|null
     */
    public function findScope(OauthScopeFindTransfer $oauthScopeFindTransfer): ?string;
}
