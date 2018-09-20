<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

interface RestApiDocumentationSchemaGeneratorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    public function addRequestSchemaForPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathSchemaDataTransfer;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    public function addResponseResourceSchemaForPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathSchemaDataTransfer;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    public function addResponseCollectionSchemaForPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathSchemaDataTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    public function getRestErrorSchemaData(): RestApiDocumentationPathSchemaDataTransfer;

    /**
     * @return array
     */
    public function getSchemas(): array;
}
