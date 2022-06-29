<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterResource;

use Symfony\Component\Routing\RouteCollection;

interface RouterResourceInterface
{
    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function __invoke(): RouteCollection;
}
