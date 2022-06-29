<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\CustomRouteRouter;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Symfony\Component\Routing\RouterInterface as SymfonyRouterInterface;

interface RouterInterface extends SymfonyRouterInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function routeRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer;
}
