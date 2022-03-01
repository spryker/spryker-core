<?php


/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

/**
 * Use this plugin for implementing routing in GlueBackendApiApplication.
 */
interface RouteMatcherPluginInterface
{
    /**
     * Specification:
     * - Routes given request to executable resource (e.g.: match HTTP path to Controller).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function route(GlueRequestTransfer $glueRequestTransfer, array $resourcePlugins): ResourceInterface;
}
