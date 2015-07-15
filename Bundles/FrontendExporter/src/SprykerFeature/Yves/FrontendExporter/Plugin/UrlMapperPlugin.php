<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\FrontendExporter\FrontendExporterDependencyContainer;
use SprykerFeature\Yves\FrontendExporter\Mapper;

/**
 * Class StorageRouterPlugin
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
