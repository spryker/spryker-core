<?php

namespace SprykerFeature\Client\FrontendExporter\Service;

use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerFeature\Client\FrontendExporter\Matcher\UrlMatcherInterface;

/**
 * @todo Rename all YvesExport Bundles to PageExport or just Export.
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class FrontendExporterClient extends AbstractClient
{

    /**
     * @return UrlMatcherInterface
     */
    public function createUrlMatcher()
    {
        return $this->getDependencyContainer()->createUrlMatcher();
    }

    public function matchUrl()
    {
        return $this->getDependencyContainer()->createUrlMatcher()->matchUrl();
    }

}
