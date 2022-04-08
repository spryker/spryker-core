<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupTransfer;
use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroup;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantProductOptionGroupMapper
{
    /**
     * @param \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroup $merchantProductOptionGroupEntity
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupTransfer $merchantProductOptionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupTransfer
     */
    public function mapMerchantProductOptionGroupEntityToMerchantProductOptionGroupTransfer(
        SpyMerchantProductOptionGroup $merchantProductOptionGroupEntity,
        MerchantProductOptionGroupTransfer $merchantProductOptionGroupTransfer
    ): MerchantProductOptionGroupTransfer {
        return $merchantProductOptionGroupTransfer->fromArray($merchantProductOptionGroupEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroup> $merchantProductOptionGroupEntities
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer $merchantProductOptionGroupCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer
     */
    public function mapMerchantProductOptionGroupEntitiesToMerchantProductOptionGroupCollectionTransfer(
        ObjectCollection $merchantProductOptionGroupEntities,
        MerchantProductOptionGroupCollectionTransfer $merchantProductOptionGroupCollectionTransfer
    ): MerchantProductOptionGroupCollectionTransfer {
        foreach ($merchantProductOptionGroupEntities as $merchantProductOptionGroupEntity) {
            $merchantProductOptionGroupCollectionTransfer->addMerchantProductOptionGroup(
                $this->mapMerchantProductOptionGroupEntityToMerchantProductOptionGroupTransfer(
                    $merchantProductOptionGroupEntity,
                    new MerchantProductOptionGroupTransfer(),
                ),
            );
        }

        return $merchantProductOptionGroupCollectionTransfer;
    }
}
