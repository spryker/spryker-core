<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategorySearch\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\ProductCategorySearch\Communication\ProductCategorySearchDependencyContainer;

/**
 * @method ProductCategorySearchDependencyContainer getDependencyContainer()
 */
class ProductCategorySearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
