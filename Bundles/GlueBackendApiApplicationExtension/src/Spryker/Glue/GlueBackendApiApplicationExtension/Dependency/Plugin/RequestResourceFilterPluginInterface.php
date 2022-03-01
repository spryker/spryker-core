<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

/**
 * Use this plugin for implementing the resource filtering GlueBackendApiApplication.
 * Each implementation needs to reduce the list of resources to one only using the `GlueRequestTransfer`.
 */
interface RequestResourceFilterPluginInterface
{
    /**
     * Specification:
     * - Filters resources matching the data in the `GlueRequestTransfer`.
     * - Responds with a resource exactly.
     * - Responds with null if no resource match the current request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface|null
     */
    public function filterResource(GlueRequestTransfer $glueRequestTransfer, array $resourcePlugins): ?ResourceInterface;
}
