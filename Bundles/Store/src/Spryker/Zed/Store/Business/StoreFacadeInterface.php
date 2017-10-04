<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

/**
 * @method \Spryker\Zed\Store\Business\StoreBusinessFactory getFactory()
 */
interface StoreFacadeInterface
{

    /**
     * Specification:
     *  - Returns currently selected store transfer
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();

    /**
     * Specification
     *  - Reads all active stores and returns list of transfers
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStores();

}
