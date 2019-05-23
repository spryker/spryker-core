<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

interface ResourceRelationshipCollectionInterface
{
    /**
     * Specification:
     *  - Add relation provider plugins, if any resourceType have relationships added which references the name provided here, this plugin will be called to populate data for that resourceType.
     * The data will be added to included field as per JSONAPI specification.
     *
     * e.g resource name = ("items", new CartItemsProductsResourceRelationship()). This relationship must add products to each cart item.
     *
     * @api
     *
     * @param string $resourceType
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipsPlugin
     *
     * @return $this
     */
    public function addRelationship(string $resourceType, ResourceRelationshipPluginInterface $resourceRelationshipsPlugin);

    /**
     * Checks if resourceType provider by given name exists.
     *
     * @api
     *
     * @param string $resourceType
     *
     * @return bool
     */
    public function hasRelationships(string $resourceType): bool;

    /**
     * Returns resourceType by given name.
     *
     * @api
     *
     * @param string $resourceType
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface[]
     */
    public function getRelationships(string $resourceType): array;
}
