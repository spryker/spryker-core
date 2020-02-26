<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer;
use Generated\Shared\Transfer\GiftCardProductConfigurationTransfer;
use Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfiguration;
use Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfiguration;
use Propel\Runtime\Collection\ObjectCollection;

class GiftCardMapper
{
    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfiguration $giftCardProductAbstractConfigurationEntity
     * @param \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer $giftCardAbstractProductConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer
     */
    public function mapGiftCardProductAbstractConfigurationEntityToGiftCardAbstractProductConfigurationTransfer(
        SpyGiftCardProductAbstractConfiguration $giftCardProductAbstractConfigurationEntity,
        GiftCardAbstractProductConfigurationTransfer $giftCardAbstractProductConfigurationTransfer
    ): GiftCardAbstractProductConfigurationTransfer {
        return $giftCardAbstractProductConfigurationTransfer->fromArray($giftCardProductAbstractConfigurationEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfiguration[] $giftCardProductAbstractConfigurationEntities
     *
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer[]
     */
    public function mapGiftCardProductAbstractConfigurationEntitiesToGiftCardAbstractProductConfigurationTransfers(ObjectCollection $giftCardProductAbstractConfigurationEntities): array
    {
        $giftCardAbstractProductConfigurationTransfers = [];

        foreach ($giftCardProductAbstractConfigurationEntities as $giftCardProductAbstractConfigurationEntity) {
            $giftCardAbstractProductConfigurationTransfers[] = $this
                ->mapGiftCardProductAbstractConfigurationEntityToGiftCardAbstractProductConfigurationTransfer(
                    $giftCardProductAbstractConfigurationEntity,
                    new GiftCardAbstractProductConfigurationTransfer()
                );
        }

        return $giftCardAbstractProductConfigurationTransfers;
    }

    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfiguration $giftCardProductConfigurationEntity
     * @param \Generated\Shared\Transfer\GiftCardProductConfigurationTransfer $giftCardProductConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationTransfer
     */
    public function mapGiftCardProductConfigurationEntityToGiftCardProductConfigurationTransfer(
        SpyGiftCardProductConfiguration $giftCardProductConfigurationEntity,
        GiftCardProductConfigurationTransfer $giftCardProductConfigurationTransfer
    ): GiftCardProductConfigurationTransfer {
        return $giftCardProductConfigurationTransfer
            ->fromArray($giftCardProductConfigurationEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfiguration[] $giftCardProductConfigurationEntities
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationTransfer[]
     */
    public function mapGiftCardProductConfigurationEntitiesToGiftCardProductConfigurationTransfers(ObjectCollection $giftCardProductConfigurationEntities): array
    {
        $giftCardProductConfigurationTransfers = [];

        foreach ($giftCardProductConfigurationEntities as $giftCardProductConfigurationEntity) {
            $giftCardProductConfigurationTransfers[] = $this->mapGiftCardProductConfigurationEntityToGiftCardProductConfigurationTransfer(
                $giftCardProductConfigurationEntity,
                new GiftCardProductConfigurationTransfer()
            );
        }

        return $giftCardProductConfigurationTransfers;
    }
}
