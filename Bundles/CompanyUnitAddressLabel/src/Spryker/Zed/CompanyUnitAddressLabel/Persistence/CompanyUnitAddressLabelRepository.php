<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\Map\SpyCompanyUnitAddressLabelTableMap;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\Map\SpyCompanyUnitAddressLabelToCompanyUnitAddressTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelPersistenceFactory getFactory()
 */
class CompanyUnitAddressLabelRepository extends AbstractRepository implements CompanyUnitAddressLabelRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function findCompanyUnitAddressLabels(): CompanyUnitAddressLabelCollectionTransfer
    {
        $companyUnitAddressLabelQuery = $this->getFactory()
            ->createCompanyUnitAddressLabelQuery();

        return $this->createCollection($companyUnitAddressLabelQuery);
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function findCompanyUnitAddressLabelsByAddress(int $idCompanyUnitAddress): CompanyUnitAddressLabelCollectionTransfer
    {
        $companyUnitAddressLabelQuery = $this->getFactory()
            ->createCompanyUnitAddressLabelQuery()
            ->useSpyCompanyUnitAddressLabelToCompanyUnitAddressQuery()
                ->filterByFkCompanyUnitAddress($idCompanyUnitAddress)
            ->endUse();

        return $this->createCollection($companyUnitAddressLabelQuery);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return int[]
     */
    public function findCompanyUnitAddressLabelIdsByAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): array
    {
        return $this->getFactory()->createCompanyUnitAddressLabelQuery()
            ->useSpyCompanyUnitAddressLabelToCompanyUnitAddressQuery()
                ->filterByFkCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress())
            ->endUse()
            ->select(
                [SpyCompanyUnitAddressLabelTableMap::COL_ID_COMPANY_UNIT_ADDRESS_LABEL]
            )->find()->getData();
    }

    /**
     * @param int $idCompanyUnitAddress
     * @param int[] $labelIds
     *
     * @return int[]
     */
    public function findCompanyUnitAddressLabelToCompanyUnitAddressRelationIdsByAddressIdAndLabelIds(
        int $idCompanyUnitAddress,
        array $labelIds
    ): array {
        return $this->getFactory()
            ->createCompanyUnitAddressLabelToCompanyUnitAddressQuery()
            ->filterByFkCompanyUnitAddress($idCompanyUnitAddress)
            ->filterByFkCompanyUnitAddressLabel_In($labelIds)
            ->select(
                [SpyCompanyUnitAddressLabelToCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS_LABEL_TO_COMPANY_UNIT_ADDRESS]
            )->find()->getData();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    protected function createCollection(ModelCriteria $query): CompanyUnitAddressLabelCollectionTransfer
    {
        $companyUnitAddressLabelTransfers = $this->buildQueryFromCriteria($query)->find();
        $companyUnitAddressLabelTransfers = new ArrayObject($companyUnitAddressLabelTransfers);

        $collectionTransfer = new CompanyUnitAddressLabelCollectionTransfer();
        $collectionTransfer->setLabels($companyUnitAddressLabelTransfers);

        return $collectionTransfer;
    }
}
