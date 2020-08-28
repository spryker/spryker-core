<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Dependency\Client;

class NavigationsRestApiToUrlStorageClientBridge implements NavigationsRestApiToUrlStorageClientInterface
{
    /**
     * @var \Spryker\Client\UrlStorage\UrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @param \Spryker\Client\UrlStorage\UrlStorageClientInterface $urlStorageClient
     */
    public function __construct($urlStorageClient)
    {
        $this->urlStorageClient = $urlStorageClient;
    }

    /**
     * @param string[] $urlCollection
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer[]
     */
    public function getUrlStorageTransferByUrls(array $urlCollection): array
    {
        return $this->urlStorageClient->getUrlStorageTransferByUrls($urlCollection);
    }
}
