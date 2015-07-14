<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\RedirectExporter\Communication;

use Generated\Yves\Ide\FactoryAutoCompletion\RedirectExporter;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Yves\RedirectExporter\Communication\ResourceCreator\RedirectResourceCreator;

/**
 * @method RedirectExporter getFactory()
 */
class RedirectExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return RedirectResourceCreator
     */
    public function createRedirectResourceCreator()
    {
        return $this->getFactory()->createResourceCreatorRedirectResourceCreator(
            $this->getLocator()
        );
    }

}
