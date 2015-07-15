<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\CmsExporter;

use Generated\Yves\Ide\FactoryAutoCompletion\CmsExporter;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Yves\CmsExporter\ResourceCreator\PageResourceCreator;

/**
 * @method CmsExporter getFactory()
 */
class CmsExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return PageResourceCreator
     */
    public function createPageResourceCreator()
    {
        return $this->getFactory()->createResourceCreatorPageResourceCreator(
            $this->getLocator()
        );
    }

}
