<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Symfony\Component\HttpFoundation\Request;

interface ResourceRouteLoaderInterface
{
    /**
     * @param string $resourceType
     * @param array $resources
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return null|array
     */
    public function load(string $resourceType, array $resources, Request $httpRequest): ?array;

    /**
     * @param string $resourceType
     * @param array $resources
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return array
     */
    public function getAvailableMethods(string $resourceType, array $resources, Request $httpRequest): array;
}
