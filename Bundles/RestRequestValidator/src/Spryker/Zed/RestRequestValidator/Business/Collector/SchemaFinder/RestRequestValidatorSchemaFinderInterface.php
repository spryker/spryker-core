<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder;

use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface;

interface RestRequestValidatorSchemaFinderInterface
{
    /**
     * @param string[] $paths
     *
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    public function findSchemas(array $paths): RestRequestValidatorToFinderAdapterInterface;

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getPaths(string $storeName): array;
}
