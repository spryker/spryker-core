<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Business\ProductFrontendExporterConnectorFacade;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Communication\ProductFrontendExporterConnectorDependencyContainer;

/**
 * Class ProductDataProcessorPlugin
 *
 * @package SprykerFeature\Zed\ProductFrontendExporterConnector\Communication\Plugin
 */
/**
 * @method ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{
    /**
     * @var ProductFrontendExporterConnectorFacade
     */
    protected $productProcessor;

    public function __construct(Factory $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->productProcessor = $this->getDependencyContainer()->getProductProcessor();
    }

    /**
     * @param array  $resultSet
     * @param array  $processedResultSet
     * @param string $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, $locale)
    {
        $processedResultSet = $this->productProcessor->buildProducts($resultSet, $locale);

        $keys = array_keys($processedResultSet);
        $resultSet = array_combine($keys, $resultSet);

        return $processedResultSet;
    }

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'product';
    }
}
