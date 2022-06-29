<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder;

use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterInterface;

interface RouterBuilderInterface
{
    /**
     * @param string $apiApplicationName
     *
     * @return \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterInterface|null
     */
    public function buildRouter(string $apiApplicationName): ?RouterInterface;
}
