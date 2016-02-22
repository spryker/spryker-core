<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
