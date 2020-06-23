<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi;

interface ContentProductAbstractListsRestApiResourceInterface
{
    /**
     * Specification:
     * - Retrieves content product abstract lists by content product abstract lists keys.
     *
     * @api
     *
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param string[] $contentProductAbstractListKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getContentProductAbstractListsByKeys(array $contentProductAbstractListKeys, string $localeName, string $storeName): array;
}
