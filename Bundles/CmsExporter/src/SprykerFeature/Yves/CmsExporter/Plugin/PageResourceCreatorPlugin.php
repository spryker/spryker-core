<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\CmsExporter\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\CmsExporter\CmsExporterDependencyContainer;
use SprykerFeature\Yves\CmsExporter\ResourceCreator\PageResourceCreator;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class PageResourceCreatorPlugin extends AbstractPlugin
{

    /**
     * @return PageResourceCreator
     */
    public function createPageResourceCreator()
    {
        return $this->getDependencyContainer()->createPageResourceCreator();
    }

}
