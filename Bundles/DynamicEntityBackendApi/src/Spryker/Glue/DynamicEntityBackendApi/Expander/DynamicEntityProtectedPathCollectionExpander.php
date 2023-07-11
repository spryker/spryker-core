<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Expander;

use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;

class DynamicEntityProtectedPathCollectionExpander implements DynamicEntityProtectedPathCollectionExpanderInterface
{
    /**
     * @var string
     */
    protected const IS_REGULAR_EXPRESSION = 'isRegularExpression';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig
     */
    protected DynamicEntityBackendApiConfig $dynamicEntityBackendApiConfig;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig $dynamicEntityBackendApiConfig
     */
    public function __construct(DynamicEntityBackendApiConfig $dynamicEntityBackendApiConfig)
    {
        $this->dynamicEntityBackendApiConfig = $dynamicEntityBackendApiConfig;
    }

    /**
     * @param array<string, mixed> $protectedPathCollection
     *
     * @return array<string, mixed>
     */
    public function expand(array $protectedPathCollection): array
    {
        $routePrefix = $this->dynamicEntityBackendApiConfig->getRoutePrefix();
        $protectedPathCollection[sprintf('/\/%s\/.+/', $routePrefix)] = [static::IS_REGULAR_EXPRESSION => true];

        return $protectedPathCollection;
    }
}
