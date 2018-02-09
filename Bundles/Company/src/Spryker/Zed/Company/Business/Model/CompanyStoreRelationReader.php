<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Company\Persistence\CompanyRepositoryInterface;

class CompanyStoreRelationReader implements CompanyStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\Company\Persistence\CompanyRepositoryInterface
     */
    protected $companyRepository;

    /**
     * @param \Spryker\Zed\Company\Persistence\CompanyRepositoryInterface $companyRepository
     */
    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer): StoreRelationTransfer
    {
        $storeRelationTransfer->requireIdEntity();
        $storeTransferCollection = $this->companyRepository->getRelatedStoresByCompanyId($storeRelationTransfer->getIdEntity());
        $idStores = $this->getIdStores($storeTransferCollection);

        $storeRelationTransfer
            ->setStores($storeTransferCollection)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeTransferCollection
     *
     * @return int[]
     */
    protected function getIdStores(ArrayObject $storeTransferCollection): array
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $storeTransferCollection->getArrayCopy());
    }
}
