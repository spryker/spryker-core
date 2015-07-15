<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\FrontendExporter\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\FrontendExporter\Service\Matcher\UrlMatcherInterface;
use SprykerFeature\Yves\FrontendExporter\Business\Model\UrlResource;

/**
 * @todo Rename all YvesExport Bundles to PageExport or just Export.
 *
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class FrontendExporterClient extends AbstractClient implements UrlMatcherInterface
{

    /**
     * @param $url
     * @param $localeName
     *
     * @return UrlResource
     */
    public function matchUrl($url, $localeName)
    {
        return $this->getDependencyContainer()->createUrlMatcher()->matchUrl($url, $localeName);
    }

}
