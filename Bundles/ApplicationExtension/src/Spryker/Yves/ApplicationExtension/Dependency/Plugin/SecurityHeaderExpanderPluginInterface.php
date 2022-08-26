<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ApplicationExtension\Dependency\Plugin;

/**
 * Provides extension capabilities for security headers.
 */
interface SecurityHeaderExpanderPluginInterface
{
    /**
     * Specification:
     * - Extends security headers before adding them to `EventDispatch`.
     *
     * @api
     *
     * @param array<string, string> $securityHeaders
     *
     * @return array<string, string>
     */
    public function expand(array $securityHeaders): array;
}
