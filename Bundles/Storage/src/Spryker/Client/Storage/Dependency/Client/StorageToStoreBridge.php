<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Dependency\Client;

class StorageToStoreBridge implements StorageToStoreBridgeInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $storeInstance;

    /**
     * @param \Spryker\Shared\Kernel\Store
     */
    public function __construct($storeInstance)
    {
        $this->storeInstance = $storeInstance;
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->storeInstance->getStoreName();
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->storeInstance->getCurrentLocale();
    }
}
