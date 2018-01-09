<?php

namespace Spryker\Zed\Company\Dependency\Facade;

interface CompanyToStoreInterface
{

    /**
     * Specification
     *  - Reads all active stores and returns list of transfers
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStores();

    /**
     * Specification:
     *  - Returns currently selected store transfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();

    /**
     * Specification
     *  - Read store by primary id from database
     *
     * @param int $idStore
     *
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById($idStore);
}