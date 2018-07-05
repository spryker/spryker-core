<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class ProductPackagingUnitToOmsFacadeBridge implements ProductPackagingUnitToOmsFacadeInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @return string[]
     */
    public function getReservedStateNames(): array
    {
        return $this->omsFacade->getReservedStateNames();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku(string $sku, StoreTransfer $storeTransfer): int
    {
        return $this->omsFacade->getOmsReservedProductQuantityForSku($sku, $storeTransfer);
    }
}
