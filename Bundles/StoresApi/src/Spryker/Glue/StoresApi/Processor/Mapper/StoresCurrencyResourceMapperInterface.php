<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiStoreCurrencyAttributesTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;

interface StoresCurrencyResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\ApiStoreCurrencyAttributesTransfer $apiStoreCurrencyAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiStoreCurrencyAttributesTransfer
     */
    public function mapCurrencyToStoresCurrencyRestAttributes(
        CurrencyTransfer $currencyTransfer,
        ApiStoreCurrencyAttributesTransfer $apiStoreCurrencyAttributesTransfer
    ): ApiStoreCurrencyAttributesTransfer;
}
