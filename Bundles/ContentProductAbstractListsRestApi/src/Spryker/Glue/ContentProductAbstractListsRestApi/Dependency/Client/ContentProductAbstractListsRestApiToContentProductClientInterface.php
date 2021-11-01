<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;

interface ContentProductAbstractListsRestApiToContentProductClientInterface
{
    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function executeProductAbstractListTypeByKey(string $contentKey, string $localeName): ?ContentProductAbstractListTypeTransfer;

    /**
     * @param array<string> $contentKeys
     * @param string $localeName
     *
     * @return array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer>
     */
    public function executeProductAbstractListTypeByKeys(array $contentKeys, string $localeName): array;
}
