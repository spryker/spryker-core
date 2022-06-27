<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthExtension\Dependency\Plugin;

interface ScopeDefinitionPluginInterface
{
    /**
     * Specification:
     * - Returns scopes the access to the resource/route will be limited by.
     *
     * @api
     *
     * @see {@link https://datatracker.ietf.org/doc/html/rfc6749#section-3.3}
     *
     * @return array<string, string>
     */
    public function getScopes(): array;
}
