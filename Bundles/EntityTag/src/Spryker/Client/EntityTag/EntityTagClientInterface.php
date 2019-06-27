<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EntityTag;

interface EntityTagClientInterface
{
    /**
     * Specification:
     * - Generates storage key.
     * - Reads from key-value storage.
     *
     * @api
     *
     * @param string $resourceName
     * @param string $resourceId
     *
     * @return string|null
     */
    public function read(string $resourceName, string $resourceId): ?string;

    /**
     * Specification:
     * - Generates storage key.
     * - Encodes resource attributes to json string.
     * - Generates hash for resource attributes.
     * - Writes to key-value storage.
     *
     * @api
     *
     * @param string $resourceName
     * @param string $resourceId
     * @param array $resourceAttributes
     *
     * @return string
     */
    public function write(string $resourceName, string $resourceId, array $resourceAttributes): string;
}
