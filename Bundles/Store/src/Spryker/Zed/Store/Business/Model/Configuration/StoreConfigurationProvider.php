<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model\Configuration;

use Spryker\Shared\Kernel\Store;

class StoreConfigurationProvider implements StoreConfigurationProviderInterface
{

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->store->getStoreName();
    }

    /**
     * @return string[]
     */
    public function getAllStoreNames()
    {
        return $this->store->getAllowedStores();
    }

    /**
     * @return string[]
     */
    public function getAvailableCurrencyIsoCodes()
    {
        return $this->store->getCurrencyIsoCodes();
    }

    /**
     * @return string
     */
    public function getSelectedLocaleIsoCode()
    {
        return $this->store->getCurrentLocale();
    }

    /**
     * @return string[]
     */
    public function getAvailableLocaleIsoCodes()
    {
        return $this->store->getLocales();
    }

}
