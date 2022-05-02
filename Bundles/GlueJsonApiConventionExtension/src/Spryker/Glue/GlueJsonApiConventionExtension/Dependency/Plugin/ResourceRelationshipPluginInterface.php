<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;

/**
 * Use this interface to implement a resource relationship.
 */
interface ResourceRelationshipPluginInterface
{
    /**
     * Specification:
     * - Adds relationships for resources.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $resources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addRelationships(array $resources, GlueRequestTransfer $glueRequestTransfer): void;

    /**
     * Specification:
     * - Resource type that the plugin will add.
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string;
}
