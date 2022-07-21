<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthExtension\Dependency\Plugin;

/**
 * Implement this interface to inform the OAuth about the set of scopes.
 * One plugin can provide multiple scopes.
 */
interface ScopeCollectorPluginInterface
{
    /**
     * Specification:
     * - Provides the set of OAuth scopes.
     *
     * @api
     *
     * @see {@link https://datatracker.ietf.org/doc/html/rfc6749#section-3.3}
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeFindTransfer>
     */
    public function provideScopes(): array;
}
