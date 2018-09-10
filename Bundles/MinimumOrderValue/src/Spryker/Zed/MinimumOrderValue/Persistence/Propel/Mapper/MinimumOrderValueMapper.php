<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
            ->setIdMinimumOrderValue($minimumOrderValueEntity->getIdMinOrderValue())
            ->setMinimumOrderValueThreshold(
                $this->mapMinimumOrderValueThresholdTransfer($minimumOrderValueTransfer, $minimumOrderValueEntity)
            )->setCurrency(
                (new CurrencyTransfer())->fromArray($minimumOrderValueEntity->getCurrency()->toArray(), true)
            )->setStore(
                (new StoreTransfer())->fromArray($minimumOrderValueEntity->getStore()->toArray(), true)
            );

        if (!$minimumOrderValueTransfer->getMinimumOrderValueThreshold()->getMinimumOrderValueType()) {
            $minimumOrderValueTransfer->getMinimumOrderValueThreshold()->setMinimumOrderValueType(new MinimumOrderValueTypeTransfer());
        }

        $minimumOrderValueTransfer->getMinimumOrderValueThreshold()->setMinimumOrderValueType(
            $minimumOrderValueTransfer->getMinimumOrderValueThreshold()->getMinimumOrderValueType()->fromArray(
                $minimumOrderValueEntity->getMinimumOrderValueType()->toArray(),
                true
            )
        );

        return $minimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     * @param \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue $minimumOrderValueEntity
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer
     */
    protected function mapMinimumOrderValueThresholdTransfer(MinimumOrderValueTransfer $minimumOrderValueTransfer, SpyMinimumOrderValue $minimumOrderValueEntity): MinimumOrderValueThresholdTransfer
    {
        $minimumOrderValueThresholdTransfer = $minimumOrderValueTransfer->getMinimumOrderValueThreshold() ?? (new MinimumOrderValueThresholdTransfer());
        $minimumOrderValueThresholdTransfer = $minimumOrderValueThresholdTransfer
            ->fromArray($minimumOrderValueEntity->toArray(), true)
            ->setFee($minimumOrderValueEntity->getFee())
            ->setThreshold($minimumOrderValueEntity->getThreshold())
            ->setMessageGlossaryKey($minimumOrderValueEntity->getMessageGlossaryKey());

        return $minimumOrderValueThresholdTransfer;
    }
}
