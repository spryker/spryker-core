<?php

namespace SprykerFeature\Zed\CategoryExporter\Communication;

use SprykerFeature\Zed\CategoryExporter\Business\CategoryExporterFacade;
use SprykerFeature\Zed\CategoryExporter\Persistence\CategoryExporterQueryContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;

/**
 * Class CategoryExporterDependencyContainer
 * @package SprykerFeature\Zed\CategoryExporter\Communication
 * @property \Generated\Zed\Ide\FactoryAutoCompletion\CategoryExporterCommunication $factory
 */
class CategoryExporterDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return CategoryExporterFacade
     */
    public function getCategoryExporterFacade()
    {
        return $this->getLocator()->categoryExporter()->facade();
    }

    /**
     * @return CategoryExporterQueryContainer
     */
    public function getCategoryExporterQueryContainer()
    {
        return $this->getLocator()->categoryExporter()->queryContainer();
    }
}
