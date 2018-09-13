<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;

interface ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return array
     */
    public function getData(array $data, MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): array;
}
