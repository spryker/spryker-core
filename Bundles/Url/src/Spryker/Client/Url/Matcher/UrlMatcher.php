<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Url\Matcher;

use Generated\Shared\Transfer\UrlCollectorStorageTransfer;
use Spryker\Client\Url\Dependency\Client\UrlToStorageClientInterface;
use Spryker\Shared\Url\KeyBuilder\UrlKeyBuilder;

class UrlMatcher implements UrlMatcherInterface
{
    /**
     * @var \Spryker\Shared\Url\KeyBuilder\UrlKeyBuilder
     */
    protected $urlKeyBuilder;

    /**
     * @var \Spryker\Client\Url\Dependency\Client\UrlToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Shared\Url\KeyBuilder\UrlKeyBuilder $urlKeyBuilder
     * @param \Spryker\Client\Url\Dependency\Client\UrlToStorageClientInterface $storageClient
     */
    public function __construct(UrlKeyBuilder $urlKeyBuilder, UrlToStorageClientInterface $storageClient)
    {
        $this->urlKeyBuilder = $urlKeyBuilder;
        $this->storageClient = $storageClient;
    }

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\UrlCollectorStorageTransfer|bool
     */
    public function findUrl($url, $localeName)
    {
        $urlDetails = $this->getUrlFromStorage($url, $localeName);

        if ($urlDetails) {
            return (new UrlCollectorStorageTransfer())->fromArray($urlDetails, true);
        }

        return false;
    }

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName)
    {
        $urlDetails = $this->getUrlFromStorage($url, $localeName);
        if ($urlDetails) {
            $data = $this->storageClient->get($urlDetails[UrlCollectorStorageTransfer::REFERENCE_KEY]);
            if ($data) {
                return [
                    'type' => $urlDetails[UrlCollectorStorageTransfer::TYPE],
                    'data' => $data,
                ];
            }
        }

        return false;
    }

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    protected function getUrlFromStorage($url, $localeName)
    {
        $url = rawurldecode($url);
        $urlKey = $this->urlKeyBuilder->generateKey($url, $localeName);

        return $this->storageClient->get($urlKey);
    }
}
