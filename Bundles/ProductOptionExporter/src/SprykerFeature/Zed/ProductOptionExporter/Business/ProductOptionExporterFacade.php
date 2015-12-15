<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\LocaleTransfer;

/**
 * @method ProductOptionExporterDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->getProcessorModel()->processDataForExport($resultSet, $processedResultSet, $locale);
    }

}
