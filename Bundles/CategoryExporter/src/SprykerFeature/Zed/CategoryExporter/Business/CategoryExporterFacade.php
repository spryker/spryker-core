<?php

namespace SprykerFeature\Zed\CategoryExporter\Business;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

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
     * @param array $resultSet
     * @param LocaleDto $locale
     * @return array
     */
    public function processCategoryNodes(array $resultSet, LocaleDto $locale)
    {
        return $this->getDependencyContainer()->createCategoryNodeProcessor()
            ->process($resultSet, $locale);
    }

    /**
     * @param array $resultSet
     * @param LocaleDto $locale
     * @return array
     */
    public function processNavigation(array $resultSet, LocaleDto $locale)
    {
        return $this->getDependencyContainer()->createNavigationProcessor()
            ->process($resultSet, $locale);
    }
}
