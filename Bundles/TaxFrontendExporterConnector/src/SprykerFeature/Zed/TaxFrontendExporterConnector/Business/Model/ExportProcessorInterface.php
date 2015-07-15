<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Business\Model;

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
