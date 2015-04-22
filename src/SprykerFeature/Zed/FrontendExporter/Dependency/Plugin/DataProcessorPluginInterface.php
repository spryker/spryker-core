<?php

namespace SprykerFeature\Zed\FrontendExporter\Dependency\Plugin;

interface DataProcessorPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType();

    /**
     * @param array  $resultSet
     * @param array  $processedResultSet
     * @param string $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, $locale);
}
