<?php

namespace SprykerFeature\Zed\ProductSearch\Communication\Plugin;

use SprykerEngine\Shared\Dto\LocaleDto;
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
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale)
    {
        $productSearchQueryContainer = $this->getDependencyContainer()->getProductSearchQueryContainer();

        return $productSearchQueryContainer->expandProductQuery($expandableQuery, $locale);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
