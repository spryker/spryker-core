<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Dependency\Client;

use Generated\Shared\Transfer\ContentQueryTransfer;

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
     * @param int $id
     * @param string $locale
     *
     * @return array|null
     */
    public function findContentQueryById(int $id, string $locale): ?ContentQueryTransfer
    {
        return $this->contentStorageClient->findContentQueryById($id, $locale);
    }
}
