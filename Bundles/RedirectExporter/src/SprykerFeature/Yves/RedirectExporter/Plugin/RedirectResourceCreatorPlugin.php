<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\RedirectExporter\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\RedirectExporter\RedirectExporterDependencyContainer;
use SprykerFeature\Yves\RedirectExporter\ResourceCreator\RedirectResourceCreator;

/**
 * @method RedirectExporterDependencyContainer getDependencyContainer()
 */
class RedirectResourceCreatorPlugin extends AbstractPlugin
{

    /**
     * @return RedirectResourceCreator
     */
    public function createRedirectResourceCreator()
    {
        return $this->getDependencyContainer()->createRedirectResourceCreator();
    }

}
