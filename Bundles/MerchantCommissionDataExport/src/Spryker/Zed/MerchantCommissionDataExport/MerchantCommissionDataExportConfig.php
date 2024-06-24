<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantCommissionDataExportConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const MERCHANT_COMMISSION_READ_BATCH_SIZE = 500;

    /**
     * @api
     *
     * @return int
     */
    public function getMerchantCommissionReadBatchSize(): int
    {
        return static::MERCHANT_COMMISSION_READ_BATCH_SIZE;
    }
}
