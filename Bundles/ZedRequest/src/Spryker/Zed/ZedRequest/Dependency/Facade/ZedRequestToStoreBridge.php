<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Dependency\Facade;

class ZedRequestToStoreBridge implements ZedRequestToStoreInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct($store)
    {
        $this->store = $store;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrencyIsoCode($currencyIsoCode)
    {
        $this->store->setCurrencyIsoCode($currencyIsoCode);
    }

    /**
     * @param string $currentLocale
     *
     * @return void
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->store->setCurrentLocale($currentLocale);
    }
}
