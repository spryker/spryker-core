<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ExecutedContentStorageTransfer;
use Spryker\Client\ContentStorage\ContentStorageClientInterface;

class ContentProductsRestApiToContentStorageClient implements ContentProductsRestApiToContentStorageClientInterface
{
    /**
     * @var \Spryker\Client\ContentStorage\ContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @param \Spryker\Client\ContentStorage\ContentStorageClientInterface $contentStorageClient
     */
    public function __construct(ContentStorageClientInterface $contentStorageClient)
    {
        $this->contentStorageClient = $contentStorageClient;
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ExecutedContentStorageTransfer|null
     */
    public function findContentById(int $idContent, string $localeName): ?ExecutedContentStorageTransfer
    {
        return $this->contentStorageClient->findContentById($idContent, $localeName);
    }
}
