<?php

namespace SprykerFeature\Sdk\FrontendExporter;

use SprykerEngine\Sdk\Kernel\AbstractSdk;
use SprykerFeature\Sdk\FrontendExporter\Matcher\UrlMatcherInterface;

/**
 * @TODO Rename all YvesExport Bundles to PageExport or just Export.
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class FrontendExporterSdk extends AbstractSdk
{
    /**
     * @return UrlMatcherInterface
     */
    public function createUrlMatcher()
    {
        return $this->getDependencyContainer()->createUrlMatcher();
    }
}
