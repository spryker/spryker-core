<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionExporter\Business\Model;

interface ExportProcessorInterface
{
    /**
     * @param array $resultSet
     * @param array $processedResultSet
     *
     * @return array
     */
    public function processDataForExport(array &$resultSet, array $processedResultSet);
}
