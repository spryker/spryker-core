<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Store\Business\StoreBusinessFactory getFactory()
 */
class StoreFacade extends AbstractFacade implements StoreFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->getFactory()->createStoreReader()->getCurrentStore();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllActiveStores()
    {
        return $this->getFactory()->createStoreReader()->getAllActiveStores();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return $this->getFactory()->createStoreReader()->getCurrencyIsoCode();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getCurrencyIsoCodes()
    {
        return $this->getFactory()->createStoreReader()->getCurrencyIsoCodes();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $storeName
     *
     * @return array
     */
    public function getAvailableCurrenciesForStore($storeName)
    {
        return $this->getFactory()->createStoreReader()->getAvailableCurrenciesForStore($storeName);
    }

}
