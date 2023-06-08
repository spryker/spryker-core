<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Persistence;

use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointPersistenceFactory getFactory()
 */
class ProductOfferServicePointRepository extends AbstractRepository implements ProductOfferServicePointRepositoryInterface
{
    /**
     * @param list<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollectionByProductOfferReferences(array $productOfferReferences): ProductOfferServiceCollectionTransfer
    {
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $productOfferServiceQuery */
        $productOfferServiceQuery = $this->getFactory()->getProductOfferServiceQuery()
            ->filterByProductOfferReference_In($productOfferReferences);

        return $this->getFactory()
            ->createProductOfferServiceMapper()
            ->mapProductOfferServiceEntitiesToProductOfferServiceCollectionTransfer(
                $productOfferServiceQuery->find(),
                new ProductOfferServiceCollectionTransfer(),
            );
    }
}
