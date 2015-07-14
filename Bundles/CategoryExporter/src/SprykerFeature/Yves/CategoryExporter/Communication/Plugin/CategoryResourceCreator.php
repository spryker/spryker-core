<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\CategoryExporter\Communication\Plugin;

use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Yves\CategoryExporter\Communication\CategoryExporterDependencyContainer;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryResourceCreator extends AbstractPlugin
{

    /**
     * @return CategoryResourceCreator
     */
    public function createCategoryResourceCreator()
    {
        return $this->getDependencyContainer()->createCategoryResourceCreator();
    }

}
