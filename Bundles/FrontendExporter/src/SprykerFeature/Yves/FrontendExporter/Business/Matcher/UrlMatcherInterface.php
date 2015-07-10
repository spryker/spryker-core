<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter\Business\Matcher;

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
