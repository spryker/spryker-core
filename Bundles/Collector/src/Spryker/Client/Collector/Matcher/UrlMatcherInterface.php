<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Collector\Matcher;

interface UrlMatcherInterface
{

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName);

}
