<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueAttribute;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueType;

class MinimumOrderValueMapper implements MinimumOrderValueMapperInterface
{
    /**
     * @param \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueType $spyMinimumOrderValueType
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function mapMinimumOrderValueTypeTransfer(
        SpyMinimumOrderValueType $spyMinimumOrderValueType,
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        $minimumOrderValueTypeTransfer->fromArray($spyMinimumOrderValueType->toArray(), true);
        $minimumOrderValueTypeTransfer->setIdMinimumOrderValueType($spyMinimumOrderValueType->getIdMinOrderValueType());

        return $minimumOrderValueTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     * @param \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue $minimumOrderValueEntity
     *
     * @return \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue
     */
    public function mapMinimumOrderValueEntity(
        MinimumOrderValueTransfer $minimumOrderValueTransfer,
        SpyMinimumOrderValue $minimumOrderValueEntity
    ): SpyMinimumOrderValue {
        if ($minimumOrderValueTransfer->getStore()) {
            $minimumOrderValueEntity
                ->setFkStore($minimumOrderValueTransfer->getStore()->getIdStore());
        }

        if (!$minimumOrderValueEntity->getMinimumOrderValueAttribute()) {
            $minimumOrderValueEntity->setMinimumOrderValueAttribute(
                new SpyMinimumOrderValueAttribute()
            );
        }

        if (!$minimumOrderValueEntity->getMinimumOrderValueAttribute()->getMinimumOrderValueType()) {
            $minimumOrderValueEntity->getMinimumOrderValueAttribute()
                ->setMinimumOrderValueType(
                    new SpyMinimumOrderValueType()
                );
        }

        if ($minimumOrderValueTransfer->getMinimumOrderValueAttribute()) {
            $minimumOrderValueEntity->getMinimumOrderValueAttribute()
                ->fromArray($minimumOrderValueTransfer->getMinimumOrderValueAttribute()->toArray());

            if ($minimumOrderValueTransfer->getMinimumOrderValueAttribute()->getCurrency()) {
                $minimumOrderValueEntity->getMinimumOrderValueAttribute()->setFkCurrency(
                    $minimumOrderValueTransfer->getMinimumOrderValueAttribute()->getCurrency()->getIdCurrency()
                );
            }

            if ($minimumOrderValueTransfer->getMinimumOrderValueAttribute()->getMinimumOrderValueType()) {
                $minimumOrderValueTypeTransfer = $minimumOrderValueTransfer->getMinimumOrderValueAttribute()->getMinimumOrderValueType();
                $minimumOrderValueEntity->getMinimumOrderValueAttribute()
                    ->getMinimumOrderValueType()
                    ->setIdMinOrderValueType($minimumOrderValueTypeTransfer->getIdMinimumOrderValueType())
                    ->setName($minimumOrderValueTypeTransfer->getName());
            }
        }

        return $minimumOrderValueEntity;
    }

    /**
     * @param \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue $minimumOrderValueEntity
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function mapMinimumOrderValueTransfer(
        SpyMinimumOrderValue $minimumOrderValueEntity,
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        $minimumOrderValueTransfer->fromArray($minimumOrderValueEntity->toArray(), true);
        $minimumOrderValueTransfer->setIdMinimumOrderValue($minimumOrderValueEntity->getIdMinOrderValue());

        return $minimumOrderValueTransfer;
    }
}
