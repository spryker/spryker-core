<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Collector\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\ProductSearch\Business\ProductSearchFacade;
use SprykerFeature\Zed\ProductSearch\Communication\ProductSearchDependencyContainer;

/**
 * @method ProductSearchDependencyContainer getDependencyContainer()
 */
class ProductSearchProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{

    /**
     * @var ProductSearchFacade
     */
    protected $transformer;

    /**
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Factory $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->transformer = $this->getDependencyContainer()->getProductsTransformer();
    }

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
        $processedResultSet = $this->transformer->createSearchProducts($resultSet, $processedResultSet, $locale);

        $keys = array_keys($processedResultSet);

        $resultSet = array_combine($keys, $resultSet);

        return $processedResultSet;
    }

}
