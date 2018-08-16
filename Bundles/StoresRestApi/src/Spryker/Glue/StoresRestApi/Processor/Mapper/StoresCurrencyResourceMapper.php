<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer;
use Spryker\Shared\Kernel\Store;

class StoresCurrencyResourceMapper implements StoresCurrencyResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer
     */
    public function mapCurrencyToStoresCurrencyRestAttributes(CurrencyTransfer $currencyTransfer, Store $store): StoreCurrencyRestAttributesTransfer
    {
        return (new StoreCurrencyRestAttributesTransfer())->fromArray(
            $currencyTransfer->toArray(),
            true
        );
    }
}
