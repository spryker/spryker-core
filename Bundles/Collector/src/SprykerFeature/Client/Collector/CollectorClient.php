<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Collector;

use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerFeature\Client\Collector\Matcher\UrlMatcherInterface;

/**
 * @todo Rename all YvesExport Bundles to PageExport or just Export.
 *
 * @method CollectorDependencyContainer getDependencyContainer()
 */
class CollectorClient extends AbstractClient implements UrlMatcherInterface
{

    /**
     * @param $url
     * @param $localeName
     */
    public function matchUrl($url, $localeName)
    {
        return $this->getDependencyContainer()->createUrlMatcher()->matchUrl($url, $localeName);
    }

}
