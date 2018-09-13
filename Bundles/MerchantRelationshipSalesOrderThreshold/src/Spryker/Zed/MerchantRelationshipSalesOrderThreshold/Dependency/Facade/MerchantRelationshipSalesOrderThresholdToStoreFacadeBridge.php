<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade;

class MerchantRelationshipSalesOrderThresholdToStoreFacadeBridge implements MerchantRelationshipSalesOrderThresholdToStoreFacadeInterface
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct($storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName)
    {
        return $this->storeFacade->getStoreByName($storeName);
    }
}
