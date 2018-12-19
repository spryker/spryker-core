<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PermissionExtension\Dependency\Plugin;

interface CustomerSecuredPatternPermissionPluginInterface
{
    /**
     * Specification:
     * - Checks if plugin applicable.
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(): bool;

    /**
     * Specification:
     * - Returns modified by rules customer secured pattern.
     *
     * @api
     *
     * @param string $securedPattern
     *
     * @return string
     */
    public function execute(string $securedPattern): string;
}
