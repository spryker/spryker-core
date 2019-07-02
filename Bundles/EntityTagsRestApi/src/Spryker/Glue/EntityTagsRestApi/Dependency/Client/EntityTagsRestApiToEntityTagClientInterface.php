<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Dependency\Client;

interface EntityTagsRestApiToEntityTagClientInterface
{
    /**
     * @param string $resourceName
     * @param string $resourceId
     * @param array $resourceAttributes
     *
     * @return string
     */
    public function write(string $resourceName, string $resourceId, array $resourceAttributes): string;

    /**
     * @param string $resourceName
     * @param string $resourceId
     *
     * @return string|null
     */
    public function read(string $resourceName, string $resourceId): ?string;
}
