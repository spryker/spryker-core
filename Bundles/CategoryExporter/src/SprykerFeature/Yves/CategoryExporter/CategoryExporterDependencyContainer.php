<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\CategoryExporter;

use Generated\Yves\Ide\FactoryAutoCompletion\CategoryExporter;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Yves\CategoryExporter\ResourceCreator\CategoryResourceCreator;

class CategoryExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var CategoryExporter
     */
    protected $factory;

    /**
     * @return CategoryResourceCreator
     */
    public function createCategoryResourceCreator()
    {
        return $this->getFactory()->createResourceCreatorCategoryResourceCreator($this->getLocator());
    }

}
