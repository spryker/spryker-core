<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiPersistenceFactory getFactory()
 */
class ProductOfferGuiRepository extends AbstractRepository implements ProductOfferGuiRepositoryInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapQueryCriteriaTransferToModelCriteria(ModelCriteria $query, QueryCriteriaTransfer $queryCriteriaTransfer): ModelCriteria
    {
        return $this->getFactory()
            ->createProductOfferQueryExpander()
            ->mapQueryCriteriaTransferToModelCriteria($query, $queryCriteriaTransfer);
    }
}
