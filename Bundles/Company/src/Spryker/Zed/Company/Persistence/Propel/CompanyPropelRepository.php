<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Company\Persistence\SpyCompanyStoreQuery;
use Spryker\Zed\Company\Persistence\CompanyRepositoryInterface;

class CompanyPropelRepository extends AbstractPropelRepository implements CompanyRepositoryInterface
{
    /**
     * @param int $idCompany
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getRelatedStoresByCompanyId($idCompany)
    {
        $companyStoreCollection = $this->queryCompanyStore()->filterByFkCompany($idCompany)->find();

        $relatedStores = new ArrayObject();

        foreach ($companyStoreCollection as $companyStore) {
            $storeTransfer = new StoreTransfer();
            $storeTransfer->fromArray($companyStore->toArray(), true);
            $relatedStores->append($storeTransfer);
        }

        return $relatedStores;
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyStoreQuery
     */
    protected function queryCompanyStore(): SpyCompanyStoreQuery
    {
        return $this->getFactory()->createCompanyStoreQuery();
    }
}
