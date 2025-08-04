<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin;

interface MultiFactorAuthPluginExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the list of multi-factor authentication plugins.
     *
     * @api
     *
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $plugins
     *
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    public function expand(array $plugins): array;
}
