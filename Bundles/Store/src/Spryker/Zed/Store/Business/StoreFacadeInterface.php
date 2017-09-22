<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
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
    public function getAllActiveStores();

    /**
     * Specification:
     *  - Returns currently selected currency iso code
     *
     * @api
     *
     * @return string
     */
    public function getCurrencyIsoCode();

    /**
     * Specification:
     *  - Returns all available currency codes for currently selected store.
     *
     * @api
     *
     * @return array
     */
    public function getCurrencyIsoCodes();


    /**
     * Specification:
     * - Returns all available currencies for given store
     *
     * @api
     *
     * @param string $storeName
     *
     * @return array
     */
    public function getAvailableCurrenciesForStore($storeName);


}
