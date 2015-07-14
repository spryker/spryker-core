<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\CmsExporter\Communication\Plugin;

use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Yves\CmsExporter\Communication\CmsExporterDependencyContainer;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class PageResourceCreator extends AbstractPlugin
{

    /**
     * @return PageResourceCreator
     */
    public function createPageResourceCreator()
    {
        return $this->getDependencyContainer()->createPageResourceCreator();
    }

}
