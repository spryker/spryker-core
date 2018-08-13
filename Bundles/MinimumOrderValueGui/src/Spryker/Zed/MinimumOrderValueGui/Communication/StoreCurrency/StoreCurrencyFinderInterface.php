<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency;

use Generated\Shared\Transfer\StoreCurrencyTransfer;

interface StoreCurrencyFinderInterface
{
    /**
     * @param string $storeCurrency
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    public function getStoreCurrencyByString(string $storeCurrency): StoreCurrencyTransfer;

    /**
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    public function getCurrentStoreCurrency(): StoreCurrencyTransfer;
}
