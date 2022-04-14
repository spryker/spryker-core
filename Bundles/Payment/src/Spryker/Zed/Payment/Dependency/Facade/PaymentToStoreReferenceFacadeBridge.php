<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class PaymentToStoreReferenceFacadeBridge implements PaymentToStoreReferenceFacadeInterface
{
    /**
     * @var \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface
     */
    protected $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface $storeReferenceFacade
     */
    public function __construct($storeReferenceFacade)
    {
        $this->storeReferenceFacade = $storeReferenceFacade;
    }

    /**
     * @param string $storeReference
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer
    {
        return $this->storeReferenceFacade->getStoreByStoreReference($storeReference);
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer
    {
        return $this->storeReferenceFacade->getStoreByStoreName($storeName);
    }
}
