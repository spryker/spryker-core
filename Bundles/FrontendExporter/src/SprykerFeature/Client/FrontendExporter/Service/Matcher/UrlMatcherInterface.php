<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\FrontendExporter\Service\Matcher;

/**
 * Interface UrlMatcherInterface
 */
interface UrlMatcherInterface
{

    /**
     * @param string $url
     * @param string $localeName
     */
    public function matchUrl($url, $localeName);

}
