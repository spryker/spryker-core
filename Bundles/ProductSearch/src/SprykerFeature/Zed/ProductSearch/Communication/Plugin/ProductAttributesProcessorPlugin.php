<?php

namespace SprykerFeature\Zed\ProductSearch\Communication\Plugin;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\ProductSearch\Business\ProductSearchFacade;
use SprykerFeature\Zed\ProductSearch\Communication\ProductSearchDependencyContainer;

/**
 * @method ProductSearchDependencyContainer getDependencyContainer()
 */
class ProductAttributesProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
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
        $this->transformer = $this->getDependencyContainer()->getAttributesTransformer();
    }


    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'product';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleDto $locale)
    {
        $processedResultSet = $this->transformer->enrichProductsWithSearchAttributes(
            $resultSet,
            $processedResultSet
        );

        return $processedResultSet;
    }
}
