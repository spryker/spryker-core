<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Dependency\Facade;

interface CurrencyToStoreInterface
{

    /**
     * @return string[]
     */
    public function getCurrencyIsoCodes();

    /**
     * @return string
     */
    public function getCurrencyIsoCode();

}
