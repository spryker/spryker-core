<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermissionCollector\Dependency\Facade;

class ProductCustomerPermissionCollectorToStoreFacadeBridge implements ProductCustomerPermissionCollectorToStoreFacadeInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Shared\Kernel\Store $storeFacade
     */
    public function __construct($storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return string
     */
    public function getCurrentStoreName(): string
    {
        return $this->storeFacade->getStoreName();
    }
}
