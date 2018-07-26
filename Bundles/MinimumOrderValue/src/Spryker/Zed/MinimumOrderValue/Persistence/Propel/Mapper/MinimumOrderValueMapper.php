<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueType;

class MinimumOrderValueMapper implements MinimumOrderValueMapperInterface
{
    /**
     * @param \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueType $spyMinimumOrderValueType
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function mapMinimumOrderValueTypeEntityToTransfer(
        SpyMinimumOrderValueType $spyMinimumOrderValueType,
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        $minimumOrderValueTypeTransfer
            ->fromArray($spyMinimumOrderValueType->toArray(), true)
            ->setIdMinimumOrderValueType($spyMinimumOrderValueType->getIdMinOrderValueType());

        return $minimumOrderValueTypeTransfer;
    }

    /**
     * @param \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue $minimumOrderValueEntity
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function mapMinimumOrderValueEntityToTransfer(
        SpyMinimumOrderValue $minimumOrderValueEntity,
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        $minimumOrderValueTransfer->fromArray($minimumOrderValueEntity->toArray(), true)
            ->setIdMinimumOrderValue($minimumOrderValueEntity->getIdMinOrderValue());

        if (!$minimumOrderValueTransfer->getMinimumOrderValueType()) {
            $minimumOrderValueTransfer->setMinimumOrderValueType(new MinimumOrderValueTypeTransfer());
        }
        $minimumOrderValueTransfer->setMinimumOrderValueType(
            $minimumOrderValueTransfer->getMinimumOrderValueType()->fromArray(
                $minimumOrderValueEntity->getMinimumOrderValueType()->toArray(),
                true
            )
        );

        $minimumOrderValueTransfer->setLocalizedMessages(new ArrayObject());
        foreach ($minimumOrderValueEntity->getSpyMinimumOrderValueLocalizedMessages() as $minimumOrderValueLocalizedMessageEntity) {
            $minimumOrderValueTransfer->addLocalizedMessage(
                (new MinimumOrderValueLocalizedMessageTransfer())->fromArray(
                    $minimumOrderValueLocalizedMessageEntity->toArray(),
                    true
                )
            );
        }

        return $minimumOrderValueTransfer;
    }
}
