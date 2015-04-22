<?php

namespace SprykerFeature\Zed\ProductCategory\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\ProductCategory\Communication\ProductCategoryDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * Class ProductCategorySearchQueryExpanderPlugin
 * @package SprykerFeature\Zed\ProductCategorySearch\Communication\Plugin
 */
/**
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 */
class ProductCategoryPathQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName)
    {
        $queryContainer = $this->getDependencyContainer()->getProductCategoryQueryContainer();

        return $queryContainer->expandProductCategoryPathQuery($expandableQuery, $localeName);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
