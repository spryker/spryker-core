<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Will be removed without replacement.
 */
interface ResourceRouterInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return array
     */
    public function matchRequest(Request $httpRequest): array;
}
