<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

interface ResourceRouterInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function matchRequest(GlueRequestTransfer $glueRequestTransfer, array $resourcePlugins): ResourceInterface;
}
