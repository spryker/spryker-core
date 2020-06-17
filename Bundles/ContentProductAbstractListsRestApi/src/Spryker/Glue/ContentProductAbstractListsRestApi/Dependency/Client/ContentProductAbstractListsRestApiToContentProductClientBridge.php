<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;

class ContentProductAbstractListsRestApiToContentProductClientBridge implements ContentProductAbstractListsRestApiToContentProductClientInterface
{
    /**
     * @var \Spryker\Client\ContentProduct\ContentProductClientInterface
     */
    protected $contentProductClient;

    /**
     * @param \Spryker\Client\ContentProduct\ContentProductClientInterface $contentProductClient
     */
    public function __construct($contentProductClient)
    {
        $this->contentProductClient = $contentProductClient;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function executeProductAbstractListTypeByKey(string $contentKey, string $localeName): ?ContentProductAbstractListTypeTransfer
    {
        return $this->contentProductClient->executeProductAbstractListTypeByKey($contentKey, $localeName);
    }

    /**
     * @phpstan-param array<string, string> $contentKeys
     *
     * @phpstan-return array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer>
     *
     * @param string[] $contentKeys
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer[]
     */
    public function executeProductAbstractListTypeByKeys(array $contentKeys, string $localeName): array
    {
        return $this->contentProductClient->executeProductAbstractListTypeByKeys($contentKeys, $localeName);
    }
}
