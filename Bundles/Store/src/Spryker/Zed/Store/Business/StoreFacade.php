<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

use Generated\Shared\Transfer\StoreTransfer;
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
    public function getAllStores()
    {
        return $this->getFactory()->createStoreReader()->getAllStores();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idStore
     *
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById($idStore)
    {
        return $this->getFactory()->createStoreReader()->getStoreById($idStore);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName)
    {
        return $this->getFactory()
            ->createStoreReader()
            ->getStoreByName($storeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer)
    {
        return $this->getFactory()
            ->createStoreReader()
            ->getStoresWithSharedPersistence($storeTransfer);
    }
}
