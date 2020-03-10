<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Model;

class PluginResourceTypeStorage implements PluginResourceTypeStorageInterface
{
    /**
     * @var array
     */
    protected static $resourceTypes = [];

    /**
     * @param string $resourceType
     * @param string $responseAttributesSchemaName
     *
     * @return void
     */
    public function add(string $resourceType, string $responseAttributesSchemaName): void
    {
        static::$resourceTypes[$resourceType] = $responseAttributesSchemaName;
    }

    /**
     * @param string $resourceType
     *
     * @return string
     */
    public function getResponseAttributesSchemaNameByResourceType(string $resourceType): string
    {
        return !empty(static::$resourceTypes[$resourceType]) ? static::$resourceTypes[$resourceType] : '';
    }
}
