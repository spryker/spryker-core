<?php

namespace SprykerFeature\Zed\ProductOptionExporter\Business\Model;

/**
 * (c) Spryker Systems GmbH copyright protected
 */
interface ExportProcessorInterface
{
    /**
     * @param array $resultSet
     * @param array $processedResultSet
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet);
}
