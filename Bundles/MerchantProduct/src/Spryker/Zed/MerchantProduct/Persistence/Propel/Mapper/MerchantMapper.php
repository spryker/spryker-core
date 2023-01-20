<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductAbstractTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantMapper
{
    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $merchantEntity
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function mapMerchantEntityToMerchantTransfer(SpyMerchant $merchantEntity, MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        return $merchantTransfer->fromArray($merchantEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductAbstractEntity
     * @param \Generated\Shared\Transfer\MerchantProductAbstractTransfer $merchantProductAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductAbstractTransfer
     */
    public function mapMerchantProductAbstractEntityToMerchantProductAbstractTransfer(
        SpyMerchantProductAbstract $merchantProductAbstractEntity,
        MerchantProductAbstractTransfer $merchantProductAbstractTransfer
    ): MerchantProductAbstractTransfer {
        return $merchantProductAbstractTransfer->fromArray($merchantProductAbstractEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractEntities
     * @param \Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer $merchantProductAbstractCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer
     */
    public function mapMerchantProductAbstractEntitiesToMerchantProductAbstractCollectionTransfer(
        ObjectCollection $merchantProductAbstractEntities,
        MerchantProductAbstractCollectionTransfer $merchantProductAbstractCollectionTransfer
    ): MerchantProductAbstractCollectionTransfer {
        foreach ($merchantProductAbstractEntities as $merchantProductAbstractEntity) {
            $merchantProductAbstractCollectionTransfer->addMerchantProductAbstract(
                $this->mapMerchantProductAbstractEntityToMerchantProductAbstractTransfer(
                    $merchantProductAbstractEntity,
                    new MerchantProductAbstractTransfer(),
                ),
            );
        }

        return $merchantProductAbstractCollectionTransfer;
    }
}
