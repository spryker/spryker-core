<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestVersionTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface ResourceVersionableInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Provide rest version transfer for resource, is additional interface for ResourceRoutePluginInterface
     * The version will be used when deciding which resource to serve on client request.
     *
     * This must be used together with resource routing plugin
     *
     * @return \Generated\Shared\Transfer\RestVersionTransfer
     */
    public function getVersion(): RestVersionTransfer;
}
