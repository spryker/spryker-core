<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Company\Persistence\CompanyPersistenceFactory;
use Spryker\Zed\Company\Persistence\CompanyRepositoryInterface;

class CompanyPropelRepository implements CompanyRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getCompanyById($idCompany): CompanyTransfer
    {
        $companyEntity = $this->getFactory()
            ->createCompanyQuery()
            ->filterByIdCompany($idCompany)
            ->findOne();

        return $this->getFactory()->createCompanyMapper()->mapCompanyEntityToTransfer($companyEntity);
    }

    /**
     * @param int $idCompany
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getRelatedStoresByCompanyId($idCompany)
    {
        $companyStoreCollection = $this->getFactory()
            ->createCompanyStoreQuery()
            ->filterByFkCompany($idCompany)
            ->find();

        $relatedStores = new ArrayObject();

        foreach ($companyStoreCollection as $companyStore) {
            $storeTransfer = new StoreTransfer();
            $storeTransfer->fromArray($companyStore->toArray(), true);
            $relatedStores->append($storeTransfer);
        }

        return $relatedStores;
    }

    /**
     * @TODO For removal.
     *
     * @return \Spryker\Zed\Company\Persistence\CompanyPersistenceFactory
     */
    protected function getFactory(): CompanyPersistenceFactory
    {
        return new CompanyPersistenceFactory();
    }
}
