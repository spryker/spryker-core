<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile\Dependency\Client;

use Generated\Shared\Transfer\ContentTypeContextTransfer;

class ContentFileToContentStorageClientBridge implements ContentFileToContentStorageClientInterface
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
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContext(int $id, string $locale): ?ContentTypeContextTransfer
    {
        return $this->contentStorageClient->findContentTypeContext($id, $locale);
    }
}
