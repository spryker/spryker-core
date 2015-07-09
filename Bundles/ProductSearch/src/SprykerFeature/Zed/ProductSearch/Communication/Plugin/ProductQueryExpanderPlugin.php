<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\ProductSearch\Communication\ProductSearchDependencyContainer;

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
        return 'abstract_product';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
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
