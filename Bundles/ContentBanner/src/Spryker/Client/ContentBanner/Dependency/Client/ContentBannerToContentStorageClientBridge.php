<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Dependency\Client;

use Generated\Shared\Transfer\ContentQueryTransfer;

class ContentBannerToContentStorageClientBridge implements ContentBannerToContentStorageClientInterface
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
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentQueryTransfer|null
     */
    public function findContentQueryById(int $idContent, string $localeName): ?ContentQueryTransfer
    {
        return $this->contentStorageClient->findContentQueryById($idContent, $localeName);
    }
}
