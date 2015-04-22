<?php

namespace SprykerFeature\Zed\CategoryExporter\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Business\Factory;

/**
 * Class CategoryExporterFacade
 *
 * @package SprykerFeature\Zed\CategoryExporter\Business
 * @property
 */
/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryExporterFacade extends AbstractFacade
{
    /**
     * @param array  $resultSet
     * @param string $locale
     * @return array
     */
    public function processCategoryNodes(array $resultSet, $locale)
    {
        return $this->getDependencyContainer()->createCategoryNodeProcessor()
            ->process($resultSet, $locale);
    }

    /**
     * @param array  $resultSet
     * @param string $locale
     * @return array
     */
    public function processNavigation(array $resultSet, $locale)
    {
        return $this->getDependencyContainer()->createNavigationProcessor()
            ->process($resultSet, $locale);
    }
}
