<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor;

use Generated\Shared\Transfer\SchemaDataTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

interface ResourceRelationshipProcessorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $transferClassName
     * @param string $responseDataSchemaName
     *
     * @return array<\Generated\Shared\Transfer\SchemaDataTransfer>
     */
    public function getRelationshipSchemaDataTransfersForPlugin(
        ResourceRoutePluginInterface $plugin,
        string $transferClassName,
        string $responseDataSchemaName
    ): array;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipPlugin
     * @param string $transferClassName
     * @param string $responseDataSchemaName
     *
     * @return array<\Generated\Shared\Transfer\SchemaDataTransfer>
     */
    public function getRelationshipSchemaDataTransfersForRelationshipPlugin(
        ResourceRelationshipPluginInterface $resourceRelationshipPlugin,
        string $transferClassName,
        string $responseDataSchemaName
    ): array;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $transferClassName
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface> $resourceRelationships
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function getIncludeDataSchemaForPlugin(
        ResourceRoutePluginInterface $plugin,
        string $transferClassName,
        array $resourceRelationships
    ): SchemaDataTransfer;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $transferClassName
     * @param string $responseSchemaName
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function getIncludeBaseSchemaForPlugin(
        ResourceRoutePluginInterface $plugin,
        string $transferClassName,
        string $responseSchemaName
    ): SchemaDataTransfer;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return array<string>
     */
    public function getResourceAttributesClassNamesFromPlugin(ResourceRoutePluginInterface $plugin): array;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getResourceRelationshipsForResourceRoutePlugin(ResourceRoutePluginInterface $plugin): array;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getNestedResourceRelationshipsForResourceRoutePlugin(ResourceRoutePluginInterface $resourceRoutePlugin): array;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipPlugin
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getResourceRelationshipsForResourceRelationshipPlugin(
        ResourceRelationshipPluginInterface $resourceRelationshipPlugin
    ): array;
}
