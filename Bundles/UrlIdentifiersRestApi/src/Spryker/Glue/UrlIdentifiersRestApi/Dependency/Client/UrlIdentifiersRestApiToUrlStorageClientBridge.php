<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client;

use Generated\Shared\Transfer\UrlRedirectStorageTransfer;

class UrlIdentifiersRestApiToUrlStorageClientBridge implements UrlIdentifiersRestApiToUrlStorageClientInterface
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
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer|null
     */
    public function findUrlStorageTransferByUrl($url)
    {
        return $this->urlStorageClient->findUrlStorageTransferByUrl($url);
    }

    /**
     * @param int $idRedirectUrl
     *
     * @return \Generated\Shared\Transfer\UrlRedirectStorageTransfer|null
     */
    public function findUrlRedirectStorageById(int $idRedirectUrl): ?UrlRedirectStorageTransfer
    {
        return $this->urlStorageClient->findUrlRedirectStorageById($idRedirectUrl);
    }
}
