<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionExporter\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;

interface ExportProcessorInterface
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processDataForExport(array &$resultSet, array $processedResultSet, LocaleTransfer $locale);

}
