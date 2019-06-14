<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Dependency\Facade;

class UserLocaleToStoreBridge implements UserLocaleToStoreInterface
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
     * @param string $localeCode
     *
     * @return void
     */
    public function setCurrentLocale($localeCode)
    {
        $this->store->setCurrentLocale($localeCode);
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->store->getCurrentLocale();
    }
}
