<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferService;
use Propel\Runtime\Collection\ObjectCollection;

class ProductOfferServiceMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferService> $productOfferServiceEntities
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function mapProductOfferServiceEntitiesToProductOfferServiceCollectionTransfer(
        ObjectCollection $productOfferServiceEntities,
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
    ): ProductOfferServiceCollectionTransfer {
        foreach ($productOfferServiceEntities as $productOfferServiceEntity) {
            $productOfferServiceCollectionTransfer->addProductOfferService($this->mapProductOfferServiceEntityToProductOfferServiceTransfer(
                $productOfferServiceEntity,
                new ProductOfferServiceTransfer(),
            ));
        }

        return $productOfferServiceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     * @param \Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferService $productOfferServiceEntity
     *
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferService
     */
    public function mapProductOfferServiceTransferToProductOfferServiceEntity(
        ProductOfferServiceTransfer $productOfferServiceTransfer,
        SpyProductOfferService $productOfferServiceEntity
    ): SpyProductOfferService {
        return $productOfferServiceEntity->fromArray($productOfferServiceTransfer->toArray());
    }

    /**
     * @param \Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferService $productOfferServiceEntity
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceTransfer
     */
    public function mapProductOfferServiceEntityToProductOfferServiceTransfer(
        SpyProductOfferService $productOfferServiceEntity,
        ProductOfferServiceTransfer $productOfferServiceTransfer
    ): ProductOfferServiceTransfer {
        return $productOfferServiceTransfer->fromArray($productOfferServiceEntity->toArray(), true);
    }
}
