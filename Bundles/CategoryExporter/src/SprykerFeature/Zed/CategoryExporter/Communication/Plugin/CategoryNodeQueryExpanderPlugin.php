<?php

namespace SprykerFeature\Zed\CategoryExporter\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Category\CategoryConfig;
use SprykerFeature\Zed\CategoryExporter\Communication\CategoryExporterDependencyContainer;
use SprykerFeature\Zed\CategoryExporter\Persistence\CategoryExporterQueryContainer;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 * @method CategoryExporterQueryContainer getQueryContainer()
 */
class CategoryNodeQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE;
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $queryContainer = $this->getQueryContainer();

        return $queryContainer->expandCategoryNodeQuery($expandableQuery, $locale);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
