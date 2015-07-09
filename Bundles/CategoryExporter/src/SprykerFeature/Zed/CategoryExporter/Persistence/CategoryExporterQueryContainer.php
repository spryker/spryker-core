<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryExporterQueryContainer extends AbstractQueryContainer
{

    /**
     * @param ModelCriteria $query
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandCategoryNodeQuery(ModelCriteria $query, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()->createCategoryNodeQueryExpander($locale)->expandQuery($query);
    }

    /**
     * @param ModelCriteria $query
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandNavigationQuery(ModelCriteria $query, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()->createNavigationQueryExpander($locale)->expandQuery($query);
    }

}
