<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue;

class MerchantRelationshipMinimumOrderValueMapper implements MerchantRelationshipMinimumOrderValueMapperInterface
{
    /**
     * @param \Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue $merchantRelationshipMinimumOrderValueEntity
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function mapMerchantRelationshipMinimumOrderValueEntityToTransfer(
        SpyMerchantRelationshipMinimumOrderValue $merchantRelationshipMinimumOrderValueEntity,
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $minimumOrderValueTypeEntity = $merchantRelationshipMinimumOrderValueEntity->getMinimumOrderValueType();
        $merchantRelationshipMinimumOrderValueTransfer->setMinimumOrderValue(
            $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue() ?? (new MinimumOrderValueTransfer())
        );

        $merchantRelationshipMinimumOrderValueTransfer->fromArray($merchantRelationshipMinimumOrderValueEntity->toArray(), true)
            ->setIdMerchantRelationshipMinimumOrderValue($merchantRelationshipMinimumOrderValueEntity->getIdMerchantRelationshipMinOrderValue())
            ->setMinimumOrderValue(
                $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->fromArray($merchantRelationshipMinimumOrderValueEntity->toArray(), true)
                    ->setMinimumOrderValueType(
                        (new MinimumOrderValueTypeTransfer())->fromArray($minimumOrderValueTypeEntity->toArray(), true)
                            ->setIdMinimumOrderValueType($minimumOrderValueTypeEntity->getIdMinOrderValueType())
                    )
            )->setStore(
                (new StoreTransfer())->fromArray($merchantRelationshipMinimumOrderValueEntity->getStore()->toArray(), true)
            )->setCurrency(
                (new CurrencyTransfer())->fromArray($merchantRelationshipMinimumOrderValueEntity->getCurrency()->toArray(), true)
            )->setMerchantRelationship(
                (new MerchantRelationshipTransfer())
                    ->fromArray($merchantRelationshipMinimumOrderValueEntity->getMerchantRelationship()->toArray(), true)
            );

        return $merchantRelationshipMinimumOrderValueTransfer;
    }
}
