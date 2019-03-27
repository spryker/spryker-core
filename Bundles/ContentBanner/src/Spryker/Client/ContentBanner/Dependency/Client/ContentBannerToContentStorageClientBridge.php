<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Dependency\Client;

use Generated\Shared\Transfer\ExecutedContentStorageTransfer;

class ContentBannerToContentStorageClientBridge implements ContentBannerToContentStorageClientInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $contentStorageClient
     */
    public function __construct($contentStorageClient)
    {
        $this->$contentStorageClient = $contentStorageClient;
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\UnexecutedContentStorageTransfer|null
     */
    public function findUnexecutedContentById(int $idContent, string $localeName): ?ExecutedContentStorageTransfer
    {
        return $this->$contentStorageClient->findUnexecutedContentById($idContent, $localeName);
    }
}
