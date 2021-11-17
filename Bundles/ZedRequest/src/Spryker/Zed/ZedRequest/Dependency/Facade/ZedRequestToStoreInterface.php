<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Dependency\Facade;

interface ZedRequestToStoreInterface
{
    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrencyIsoCode($currencyIsoCode);

    /**
     * @param string $currentLocale
     *
     * @return void
     */
    public function setCurrentLocale($currentLocale);
}
