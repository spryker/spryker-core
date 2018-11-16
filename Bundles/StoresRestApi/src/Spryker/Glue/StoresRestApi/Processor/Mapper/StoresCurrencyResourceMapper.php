<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer;

class StoresCurrencyResourceMapper implements StoresCurrencyResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer
     */
    public function mapCurrencyToStoresCurrencyRestAttributes(CurrencyTransfer $currencyTransfer): StoreCurrencyRestAttributesTransfer
    {
        return (new StoreCurrencyRestAttributesTransfer())->fromArray(
            $currencyTransfer->toArray(),
            true
        );
    }
}
