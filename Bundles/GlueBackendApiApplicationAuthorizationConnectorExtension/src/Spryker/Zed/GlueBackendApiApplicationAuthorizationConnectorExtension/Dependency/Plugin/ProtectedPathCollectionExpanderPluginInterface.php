<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin;

/**
 * Expands a list of protected endpoints.
 */
interface ProtectedPathCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands a list of protected endpoints with additional data.
     *
     * @api
     *
     * @param array<string, mixed> $protectedPathCollection
     *
     * @return array<string, mixed>
     */
    public function expand(array $protectedPathCollection): array;
}
