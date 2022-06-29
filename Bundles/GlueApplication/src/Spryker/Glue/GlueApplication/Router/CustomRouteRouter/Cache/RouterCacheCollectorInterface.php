<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Cache;

interface RouterCacheCollectorInterface
{
    /**
     * @param array<string> $apiApplications
     *
     * @return void
     */
    public function warmUp(array $apiApplications = []): void;
}
