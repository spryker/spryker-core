<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter\Business;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductOptionExporterFacadeInterface
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function processDataForExport(array &$resultSet, array $processedResultSet, LocaleTransfer $locale);

}
