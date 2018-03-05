<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
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
    public function findCompanyUnitAddressLabels()
    {
        $companyUnitAddressLabelQuery = $this->getFactory()
            ->createCompanyUnitAddressLabelQuery();

        return $this->createCollection($companyUnitAddressLabelQuery);
    }

    /**
     * @param int $idCompanyUnitAddressLabel
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function findCompanyUnitAddressLabelsByAddress(int $idCompanyUnitAddressLabel)
    {
        $companyUnitAddressLabelQuery = $this->getFactory()
            ->createCompanyUnitAddressLabelQuery()
            ->useSpyCompanyUnitAddressLabelToCompanyUnitAddressQuery()
                ->filterByFkCompanyUnitAddress($idCompanyUnitAddressLabel)
            ->endUse();

        return $this->createCollection($companyUnitAddressLabelQuery);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    protected function createCollection(ModelCriteria $query)
    {
        $companyUnitAddressLabelTransfers = $this->buildQueryFromCriteria($query)->find();
        $companyUnitAddressLabelTransfers = new ArrayObject($companyUnitAddressLabelTransfers);

        $collectionTransfer = new CompanyUnitAddressLabelCollectionTransfer();
        $collectionTransfer->setLabels($companyUnitAddressLabelTransfers);

        return $collectionTransfer;
    }
}
