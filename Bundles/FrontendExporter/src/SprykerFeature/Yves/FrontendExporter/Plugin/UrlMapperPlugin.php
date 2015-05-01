<?php

namespace SprykerFeature\Yves\FrontendExporter\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\FrontendExporter\FrontendExporterDependencyContainer;
use SprykerFeature\Yves\FrontendExporter\Mapper;

/**
 * Class StorageRouterPlugin
 * @package SprykerFeature\Yves\YvesExport
 */
/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class UrlMapperPlugin extends AbstractPlugin
{
    /**
     * @return Mapper\UrlMapper
     */
    public function createUrlMapper()
    {
        return $this->getDependencyContainer()->createUrlMapper();
    }
}
