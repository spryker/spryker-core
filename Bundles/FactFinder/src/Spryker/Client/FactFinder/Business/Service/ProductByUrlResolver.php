<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Service;

use Spryker\Client\Collector\Matcher\UrlMatcher;
use Spryker\Client\Storage\StorageClientInterface;

class ProductByUrlResolver
{

    /**
     * @var \Spryker\Client\Collector\Matcher\UrlMatcher
     */
    protected $urlMatcher;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $keyValueReader;

    /**
     * @param \Spryker\Client\Collector\Matcher\UrlMatcher $urlMatcher
     * @param \Spryker\Client\Storage\StorageClientInterface $keyValueReader
     */
    public function __construct(
        UrlMatcher $urlMatcher,
        StorageClientInterface $keyValueReader
    ) {
        $this->urlMatcher = $urlMatcher;
        $this->keyValueReader = $keyValueReader;
    }

    /**
     * @param string $locale
     * @param string $lang
     * @param string $sku
     * @return array
     */
    public function getProductDataByLocaleAndSku($locale, $lang, $sku)
    {
        $url = "/" . $lang . "/" . $sku;

        $urlDetails = $this->urlMatcher
            ->matchUrl($url, $locale);

        if ($urlDetails) {
            return $urlDetails['data'];
        }

        return [];
    }

}
