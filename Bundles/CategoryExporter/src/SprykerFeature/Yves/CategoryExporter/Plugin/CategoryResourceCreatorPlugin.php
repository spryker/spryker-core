<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\CategoryExporter\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\CategoryExporter\CategoryExporterDependencyContainer;
use SprykerFeature\Yves\CategoryExporter\ResourceCreator\CategoryResourceCreator;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryResourceCreatorPlugin extends AbstractPlugin
{

    /**
     * @return CategoryResourceCreator
     */
    public function createCategoryResourceCreator()
    {
        return $this->getDependencyContainer()->createCategoryResourceCreator();
    }

}
