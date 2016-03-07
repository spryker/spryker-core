<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionExporter\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOptionExporter\Business\ProductOptionExporterBusinessFactory getFactory()
 */
class ProductOptionExporterFacade extends AbstractFacade implements ProductOptionExporterFacadeInterface
{

    /**
     * @api
     *
     * @param array $resultSet
     * @param array $processedResultSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function processDataForExport(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        return $this->getFactory()->createProcessorModel()->processDataForExport($resultSet, $processedResultSet, $locale);
    }

}
