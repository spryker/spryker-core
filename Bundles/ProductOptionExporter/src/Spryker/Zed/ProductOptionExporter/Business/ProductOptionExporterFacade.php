<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\LocaleTransfer;

/**
 * @method ProductOptionExporterBusinessFactory getFactory()
 */
class ProductOptionExporterFacade extends AbstractFacade
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processDataForExport(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        return $this->getFactory()->getProcessorModel()->processDataForExport($resultSet, $processedResultSet, $locale);
    }

}
