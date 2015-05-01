<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Communication\Plugin;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\ProductCategory\Communication\ProductCategoryDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 */
class ProductCategoryBreadcrumbQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'product';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale)
    {
        $queryContainer = $this->getDependencyContainer()->getProductCategoryQueryContainer();

        return $queryContainer->expandProductCategoryPathQuery($expandableQuery, $locale, false);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
