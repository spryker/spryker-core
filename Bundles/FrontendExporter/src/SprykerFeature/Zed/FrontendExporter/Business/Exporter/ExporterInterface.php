<?php
namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResult;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;

interface ExporterInterface
{
    /**
     * @param DataProcessorPluginInterface $processor
     */
    public function addDataProcessor(DataProcessorPluginInterface $processor);

    /**
     * @param QueryExpanderPluginInterface $queryExpander
     */
    public function addQueryExpander(QueryExpanderPluginInterface $queryExpander);

    /**
     * @param string $type
     * @param LocaleDto $locale
     *
     * @return BatchResult
     */
    public function exportByType($type, LocaleDto $locale);
}
