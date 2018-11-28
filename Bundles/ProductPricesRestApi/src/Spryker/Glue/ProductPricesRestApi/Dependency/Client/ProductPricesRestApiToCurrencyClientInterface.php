<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Dependency\Client;

interface ProductPricesRestApiToCurrencyClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent();

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode(string $currencyIsoCode): void;
}
