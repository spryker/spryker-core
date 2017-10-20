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
     * @param string $isoCode
     *
     * @return void
     */
    public function setCurrencyIsoCode($isoCode)
    {
        $this->store->setCurrencyIsoCode($isoCode);
    }

    /**
     * @param string $localeCode
     *
     * @return void
     */
    public function setCurrentLocale($localeCode)
    {
        $this->store->setCurrentLocale($localeCode);
    }
}
