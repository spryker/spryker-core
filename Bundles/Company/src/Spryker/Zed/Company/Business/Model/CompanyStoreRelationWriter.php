<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business\Model;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Company\Persistence\CompanyEntityManagerInterface;

class CompanyStoreRelationWriter implements CompanyStoreRelationWriterInterface
{
    /**
     * @var \Spryker\Zed\Company\Persistence\CompanyEntityManagerInterface
     */
    protected $companyEntityManager;

    /**
     * @var \Spryker\Zed\Company\Business\Model\CompanyStoreRelationReaderInterface
     */
    protected $companyStoreRelationReader;

    /**
     * @param \Spryker\Zed\Company\Persistence\CompanyEntityManagerInterface $companyEntityManager
     * @param \Spryker\Zed\Company\Business\Model\CompanyStoreRelationReaderInterface $companyStoreRelationReader
     */
    public function __construct(
        CompanyEntityManagerInterface $companyEntityManager,
        CompanyStoreRelationReaderInterface $companyStoreRelationReader
    ) {
        $this->companyEntityManager = $companyEntityManager;
        $this->companyStoreRelationReader = $companyStoreRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $storeRelationTransfer
     *
     * @return void
     */
    public function save(StoreRelationTransfer $storeRelationTransfer = null): void
    {
        if ($storeRelationTransfer === null) {
            return;
        }

        $storeRelationTransfer->requireIdEntity();
        $currentIdStores = $this->getIdStoresByIdCompany($storeRelationTransfer->getIdEntity());
        $requestedIdStores = $this->findStoreRelationIdStores($storeRelationTransfer);

        if (count($requestedIdStores) === 0) {
            return;
        }

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);
        $this->companyEntityManager->addStores($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->companyEntityManager->removeStores($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return int[]
     */
    protected function findStoreRelationIdStores(StoreRelationTransfer $storeRelationTransfer): array
    {
        if ($storeRelationTransfer->getIdStores() === null) {
            return [];
        }

        return $storeRelationTransfer->getIdStores();
    }

    /**
     * @param int $idCompany
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getIdStoresByIdCompany($idCompany)
    {
        $storeRelationTransfer = new StoreRelationTransfer();
        $storeRelationTransfer->setIdEntity($idCompany);
        $storeRelations = $this->companyStoreRelationReader->getStoreRelation($storeRelationTransfer);

        $idStores = [];

        foreach ($storeRelations->getStores() as $store) {
            $idStores[] = $store->getIdStore();
        }

        return $idStores;
    }
}
