<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthScopeFindTransfer;

interface ScopeFinderPluginInterface
{
    /**
     * Specification:
     * - Checks if the current plugin can be applied to the current request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @return bool
     */
    public function isServing(OauthScopeFindTransfer $oauthScopeFindTransfer): bool;

    /**
     * Specification:
     * - Finds scope by identifier.
     * - Returns identifier if scope exists, null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @return string|null
     */
    public function findScope(OauthScopeFindTransfer $oauthScopeFindTransfer): ?string;
}
