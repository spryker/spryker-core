<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency\Facade;

class ShipmentToStoreBridge implements ShipmentToStoreInterface
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
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return $this->store->getCurrencyIsoCode();
    }
}
