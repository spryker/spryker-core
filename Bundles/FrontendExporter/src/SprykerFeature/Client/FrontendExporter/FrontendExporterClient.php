<?php

namespace SprykerFeature\Client\FrontendExporter;

use SprykerEngine\Client\Kernel\AbstractStub;
use SprykerFeature\Client\FrontendExporter\Matcher\UrlMatcherInterface;

/**
 * @TODO Rename all YvesExport Bundles to PageExport or just Export.
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class FrontendExporterStub extends AbstractStub
{
    /**
     * @return UrlMatcherInterface
     */
    public function createUrlMatcher()
    {
        return $this->getDependencyContainer()->createUrlMatcher();
    }
}
