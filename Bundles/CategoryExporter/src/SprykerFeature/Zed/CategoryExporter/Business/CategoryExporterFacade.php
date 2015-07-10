<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryExporterFacade extends AbstractFacade
{

    /**
     * @param array $resultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processCategoryNodes(array $resultSet, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()->createCategoryNodeProcessor()
            ->process($resultSet, $locale);
    }

    /**
     * @param array $resultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processNavigation(array $resultSet, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()->createNavigationProcessor()
            ->process($resultSet, $locale);
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandCategoryNodeQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()->createQueryExpander()->expandCategoryNodeQuery($expandableQuery, $locale);
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandNavigationQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()->createQueryExpander()->expandNavigationQuery($expandableQuery, $locale);
    }

}
