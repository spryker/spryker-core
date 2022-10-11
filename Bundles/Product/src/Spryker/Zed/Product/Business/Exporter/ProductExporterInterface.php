<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Exporter;

use Generated\Shared\Transfer\ProductExportCriteriaTransfer;

interface ProductExporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductExportCriteriaTransfer $productExportCriteriaTransfer
     *
     * @return void
     */
    public function export(ProductExportCriteriaTransfer $productExportCriteriaTransfer): void;
}
