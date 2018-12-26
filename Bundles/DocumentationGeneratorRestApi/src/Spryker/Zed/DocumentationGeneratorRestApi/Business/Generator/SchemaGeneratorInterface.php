<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

interface SchemaGeneratorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return string
     */
    public function addRequestSchemaForPlugin(ResourceRoutePluginInterface $plugin): string;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string|null $transferClassName
     *
     * @return string
     */
    public function addResponseResourceSchemaForPlugin(ResourceRoutePluginInterface $plugin, ?string $transferClassName = null): string;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string|null $transferClassName
     *
     * @return string
     */
    public function addResponseCollectionSchemaForPlugin(ResourceRoutePluginInterface $plugin, ?string $transferClassName = null): string;

    /**
     * @return string
     */
    public function getRestErrorSchemaData(): string;

    /**
     * @return array
     */
    public function getSchemas(): array;
}
