<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Storage;

class ResourceSchemaNameStorage implements ResourceSchemaNameStorageInterface
{
    /**
     * @var string[]
     */
    protected static $resourceTypes = [];

    /**
     * @param string $resourceType
     * @param string $responseAttributesSchemaName
     *
     * @return void
     */
    public function addResourceSchemaName(string $resourceType, string $responseAttributesSchemaName): void
    {
        static::$resourceTypes[$resourceType] = $responseAttributesSchemaName;
    }

    /**
     * @param string $resourceType
     *
     * @return string
     */
    public function getResourceSchemaNameByResourceType(string $resourceType): string
    {
        return static::$resourceTypes[$resourceType] ?? '';
    }
}
