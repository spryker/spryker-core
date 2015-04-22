<?php

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\Model;

interface ExportProcessorInterface
{
    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet);
}