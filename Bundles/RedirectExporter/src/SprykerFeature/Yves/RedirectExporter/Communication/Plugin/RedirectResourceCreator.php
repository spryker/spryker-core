<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\RedirectExporter\Communication\Plugin;

use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Yves\RedirectExporter\Communication\RedirectExporterDependencyContainer;

/**
 * @method RedirectExporterDependencyContainer getDependencyContainer()
 */
class RedirectResourceCreator extends AbstractPlugin
{

    /**
     * @return RedirectResourceCreator
     */
    public function createRedirectResourceCreator()
    {
        return $this->getDependencyContainer()->createRedirectResourceCreator();
    }

}
