<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model\Configuration;

interface StoreConfigurationProviderInterface
{

    /**
     * @return string
     */
    public function getStoreName();

    /**
     * @return string[]
     */
    public function getAllStoreNames();

    /**
     * @return string[]
     */
    public function getAvailableCurrencyIsoCodes();

    /**
     * @return string
     */
    public function getSelectedLocaleIsoCode();

    /**
     * @return string[]
     */
    public function getAvailableLocaleIsoCodes();

}
