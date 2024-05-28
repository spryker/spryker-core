<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Exporter;

use Generated\Shared\Transfer\MerchantExportCriteriaTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface MerchantExporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer
     *
     * @return void
     */
    public function export(MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer): void;
}
