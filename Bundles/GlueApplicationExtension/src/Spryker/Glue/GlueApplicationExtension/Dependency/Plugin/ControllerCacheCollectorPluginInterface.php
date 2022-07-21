<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

/**
 * Use this plugin interface to collect controllers cache for API applications.
 */
interface ControllerCacheCollectorPluginInterface
{
    /**
     * Specification:
     * - Returns controllers configuration.
     * - Configuration structure:
     *  [
     *       "APPLICATION" => [
     *           "CONTROLLER:PATH:METHOD" => [
     *               \Generated\Shared\Transfer\ApiControllerConfigurationTransfer,
     *               ...
     *           ],
     *           ...
     *       ],
     *       ...
     *   ]
     *
     * @api
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    public function getControllerConfiguration(): array;
}
