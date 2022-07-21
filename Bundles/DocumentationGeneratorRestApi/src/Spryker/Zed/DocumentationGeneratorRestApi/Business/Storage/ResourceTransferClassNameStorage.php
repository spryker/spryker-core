<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Storage;

class ResourceTransferClassNameStorage implements ResourceTransferClassNameStorageInterface
{
    /**
     * @var array<string, string>
     */
    protected static $transferClassNames = [];

    /**
     * @param string $resourceType
     * @param string $transferClassName
     *
     * @return void
     */
    public function addResourceTransferClassName(string $resourceType, string $transferClassName): void
    {
        static::$transferClassNames[$resourceType] = $transferClassName;
    }

    /**
     * @param string $resourceType
     *
     * @return string|null
     */
    public function getResourceTransferClassName(string $resourceType): ?string
    {
        return static::$transferClassNames[$resourceType] ?? null;
    }
}
