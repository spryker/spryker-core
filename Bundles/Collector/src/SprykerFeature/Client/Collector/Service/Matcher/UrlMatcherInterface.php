<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Collector\Service\Matcher;

interface UrlMatcherInterface
{

    /**
     * @param string $url
     * @param string $localeName
     */
    public function matchUrl($url, $localeName);

}
