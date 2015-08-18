<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Collector\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Collector\Service\Matcher\UrlMatcherInterface;

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
