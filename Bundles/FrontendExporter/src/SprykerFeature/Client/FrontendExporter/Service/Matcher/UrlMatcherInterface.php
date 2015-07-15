<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\FrontendExporter\Service\Matcher;

use SprykerFeature\Yves\FrontendExporter\Business\Model\UrlResource;

/**
 * Interface UrlMatcherInterface
 */
interface UrlMatcherInterface
{

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return UrlResource
     */
    public function matchUrl($url, $localeName);

}
