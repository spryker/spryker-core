<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

interface RestApiDocumentationSchemaGeneratorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return string
     */
    public function addRequestSchemaForPlugin(ResourceRoutePluginInterface $plugin): string;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return string
     */
    public function addResponseResourceSchemaForPlugin(ResourceRoutePluginInterface $plugin): string;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return string
     */
    public function addResponseCollectionSchemaForPlugin(ResourceRoutePluginInterface $plugin): string;

    /**
     * @return string
     */
    public function getRestErrorSchemaData(): string;

    /**
     * @return array
     */
    public function getSchemas(): array;
}
