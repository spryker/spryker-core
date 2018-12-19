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
     * - Returns true if customer is logged in.
     * - Returns false if customer is logged out.
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(): bool;

    /**
     * Specification:
     * - Returns customer secured pattern for unauthenticated users after applied customer access permissions.
     *
     * @api
     *
     * @param string $securedPattern
     *
     * @return string
     */
    public function execute(string $securedPattern): string;
}
