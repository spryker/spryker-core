<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Mapper;

use Generated\Shared\Transfer\StoreWithCurrencyTransfer;

interface StoreWithCurrenciesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer $storeWithCurrencyTransfer
     *
     * @return array
     */
    public function mapStoreWithCurrencyTransferToArrayWithTimezoneText(StoreWithCurrencyTransfer $storeWithCurrencyTransfer): array;
}
