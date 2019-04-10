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
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function findContentProductAbstractListTypeById(int $idContent, string $localeName): ?ContentProductAbstractListTypeTransfer
    {
        return $this->contentProductClient->findContentProductAbstractListTypeById($idContent, $localeName);
    }
}
