<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter\Communication\Plugin;

use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Yves\FrontendExporter\Communication\FrontendExporterDependencyContainer;
use SprykerFeature\Yves\FrontendExporter\Communication\Mapper;

/**
 * Class StorageRouterPlugin
 */
/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class UrlMapper extends AbstractPlugin
{

    /**
     * @return Mapper\UrlMapper
     */
    public function createUrlMapper()
    {
        return $this->getDependencyContainer()->createUrlMapper();
    }

}
