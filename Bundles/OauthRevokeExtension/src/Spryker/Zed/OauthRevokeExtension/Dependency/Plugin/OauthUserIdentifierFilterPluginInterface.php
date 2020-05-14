<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevokeExtension\Dependency\Plugin;

interface OauthUserIdentifierFilterPluginInterface
{
    /**
     * Specification:
     * - Executes plugins before a refresh token is saved.
     * - Returns filtered array.
     *
     * @api
     *
     * @param array $userIdentifier
     *
     * @return array
     */
    public function filter(array $userIdentifier): array;
}
