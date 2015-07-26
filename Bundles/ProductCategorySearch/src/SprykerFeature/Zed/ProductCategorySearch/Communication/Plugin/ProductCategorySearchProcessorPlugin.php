<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategorySearch\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Collector\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\ProductCategorySearch\Communication\ProductCategorySearchDependencyContainer;

/**
 * Class ProductCategoryProcessorPlugin
 */
/**
 * @method ProductCategorySearchDependencyContainer getDependencyContainer()
 */
class ProductCategorySearchProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'abstract_product';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        $facade = $this->getDependencyContainer()->getProductCategorySearchFacade();

        return $facade->processProductCategorySearchData($resultSet, $processedResultSet, $locale);
    }

}
