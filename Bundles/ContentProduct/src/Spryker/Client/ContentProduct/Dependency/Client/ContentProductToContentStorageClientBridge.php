<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Dependency\Client;

use Generated\Shared\Transfer\ContentTypeContextTransfer;

class ContentProductToContentStorageClientBridge implements ContentProductToContentStorageClientInterface
{
    /**
     * @var \Spryker\Client\ContentStorage\ContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @param \Spryker\Client\ContentStorage\ContentStorageClientInterface $contentStorageClient
     */
    public function __construct($contentStorageClient)
    {
        $this->contentStorageClient = $contentStorageClient;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContextByKey(string $contentKey, string $localeName): ?ContentTypeContextTransfer
    {
        return $this->contentStorageClient->findContentTypeContextByKey($contentKey, $localeName);
    }

    /**
     * @param array<string> $contentKeys
     * @param string $localeName
     *
     * @return array<string, \Generated\Shared\Transfer\ContentTypeContextTransfer>
     */
    public function getContentTypeContextByKeys(array $contentKeys, string $localeName): array
    {
        return $this->contentStorageClient->getContentTypeContextByKeys($contentKeys, $localeName);
    }
}
