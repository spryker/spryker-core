<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Collector\Matcher;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

/**
 * @deprecated use \Spryker\Client\Url\Matcher\UrlMatcher
 */
class UrlMatcher implements UrlMatcherInterface
{
    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $urlKeyBuilder;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $keyValueReader;

    /**
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $urlKeyBuilder
     * @param \Spryker\Client\Storage\StorageClientInterface $keyValueReader
     */
    public function __construct(KeyBuilderInterface $urlKeyBuilder, StorageClientInterface $keyValueReader)
    {
        $this->urlKeyBuilder = $urlKeyBuilder;
        $this->keyValueReader = $keyValueReader;
    }

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName)
    {
        $url = rawurldecode($url);
        $urlKey = $this->urlKeyBuilder->generateKey($url, $localeName);
        $urlDetails = $this->keyValueReader->get($urlKey);
        if ($urlDetails) {
            $data = $this->keyValueReader->get($urlDetails['reference_key']);
            if ($data) {
                return [
                    'type' => $urlDetails['type'],
                    'data' => $data,
                ];
            }
        }

        return false;
    }
}
