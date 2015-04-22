<?php

namespace SprykerFeature\Zed\ProductSearch\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductSearch\Communication\ProductSearchDependencyContainer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method ProductSearchDependencyContainer getDependencyContainer()
 */
class ProductQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
        $productSearchQueryContainer = $this->getDependencyContainer()->getProductSearchQueryContainer();

        return $productSearchQueryContainer->expandProductQuery($expandableQuery, $localeName);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
