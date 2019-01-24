<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Dependency\Client;

class ContentBannersRestApiToContentStorageClientBridge implements ContentBannersRestApiToContentStorageClientInterface
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
     * @param string $localeName
     *
     * @return array|null
     */
    public function findContentStorageData($id, $localeName)
    {
        return $this->contentStorageClient->findContentById($id, $localeName);
    }
}
