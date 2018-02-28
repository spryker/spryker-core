<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Company\Persistence\CompanyPersistenceFactory getFactory()
 */
class CompanyRepository extends AbstractRepository implements CompanyRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCompany
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getRelatedStoresByCompanyId(int $idCompany)
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
}
