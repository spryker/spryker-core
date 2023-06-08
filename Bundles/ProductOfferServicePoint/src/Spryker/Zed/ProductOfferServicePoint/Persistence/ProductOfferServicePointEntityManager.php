<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Persistence;

use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferService;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointPersistenceFactory getFactory()
 */
class ProductOfferServicePointEntityManager extends AbstractEntityManager implements ProductOfferServicePointEntityManagerInterface
{
    /**
     * @param string $productOfferReference
     * @param list<string> $serviceUuids
     *
     * @return void
     */
    public function deleteProductOfferServicesByProductOfferReferenceAndServiceUuids(string $productOfferReference, array $serviceUuids): void
    {
        $this->getFactory()
            ->getProductOfferServiceQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->filterByServiceUuid_In($serviceUuids)
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceTransfer
     */
    public function createProductOfferService(ProductOfferServiceTransfer $productOfferServiceTransfer): ProductOfferServiceTransfer
    {
        $productOfferServiceMapper = $this->getFactory()->createProductOfferServiceMapper();
        $productOfferServiceEntity = $productOfferServiceMapper->mapProductOfferServiceTransferToProductOfferServiceEntity(
            $productOfferServiceTransfer,
            new SpyProductOfferService(),
        );

        $productOfferServiceEntity->save();

        return $productOfferServiceMapper->mapProductOfferServiceEntityToProductOfferServiceTransfer(
            $productOfferServiceEntity,
            $productOfferServiceTransfer,
        );
    }
}
