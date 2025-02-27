<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer;
use Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer;
use Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer;
use Generated\Shared\Transfer\GiftCardProductConfigurationTransfer;
use Generated\Shared\Transfer\PaymentGiftCardTransfer;
use Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfiguration;
use Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfiguration;
use Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

class GiftCardMapper
{
    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfiguration $giftCardProductAbstractConfigurationEntity
     * @param \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer $giftCardAbstractProductConfigurationForProductAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer
     */
    public function mapGiftCardProductAbstractConfigurationEntityToGiftCardAbstractProductConfigurationForProductAbstractTransfer(
        SpyGiftCardProductAbstractConfiguration $giftCardProductAbstractConfigurationEntity,
        GiftCardAbstractProductConfigurationForProductAbstractTransfer $giftCardAbstractProductConfigurationForProductAbstractTransfer
    ): GiftCardAbstractProductConfigurationForProductAbstractTransfer {
        $giftCardAbstractProductConfigurationTransfer = new GiftCardAbstractProductConfigurationTransfer();
        $giftCardAbstractProductConfigurationTransfer->fromArray($giftCardProductAbstractConfigurationEntity->toArray(), true);

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $firstProductAbstractEntity */
        $firstProductAbstractEntity = $giftCardProductAbstractConfigurationEntity
            ->getSpyGiftCardProductAbstractConfigurationLinks()
            ->getIterator()
            ->current()
            ->getSpyProductAbstract();

        return $giftCardAbstractProductConfigurationForProductAbstractTransfer
            ->setGiftCardAbstractProductConfiguration($giftCardAbstractProductConfigurationTransfer)
            ->setAbstractSku($firstProductAbstractEntity->getSku());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfiguration> $giftCardProductAbstractConfigurationEntities
     *
     * @return array<\Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer>
     */
    public function mapGiftCardProductAbstractConfigurationEntitiesToGiftCardAbstractProductConfigurationForProductAbstractTransfers(
        ObjectCollection $giftCardProductAbstractConfigurationEntities
    ): array {
        $giftCardAbstractProductConfigurationForProductAbstractTransfers = [];

        foreach ($giftCardProductAbstractConfigurationEntities as $giftCardProductAbstractConfigurationEntity) {
            $giftCardAbstractProductConfigurationForProductAbstractTransfers[] = $this
                ->mapGiftCardProductAbstractConfigurationEntityToGiftCardAbstractProductConfigurationForProductAbstractTransfer(
                    $giftCardProductAbstractConfigurationEntity,
                    new GiftCardAbstractProductConfigurationForProductAbstractTransfer(),
                );
        }

        return $giftCardAbstractProductConfigurationForProductAbstractTransfers;
    }

    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfiguration $giftCardProductConfigurationEntity
     * @param \Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer $giftCardProductConfigurationForProductTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer
     */
    public function mapGiftCardProductConfigurationEntityToGiftCardProductConfigurationForProductTransfer(
        SpyGiftCardProductConfiguration $giftCardProductConfigurationEntity,
        GiftCardProductConfigurationForProductTransfer $giftCardProductConfigurationForProductTransfer
    ): GiftCardProductConfigurationForProductTransfer {
        $giftCardProductConfigurationTransfer = new GiftCardProductConfigurationTransfer();
        $giftCardProductConfigurationTransfer->fromArray($giftCardProductConfigurationEntity->toArray(), true);

        /** @var \Orm\Zed\Product\Persistence\SpyProduct $firstProductEntity */
        $firstProductEntity = $giftCardProductConfigurationEntity
            ->getSpyGiftCardProductConfigurationLinks()
            ->getIterator()
            ->current()
            ->getSpyProduct();

        return $giftCardProductConfigurationForProductTransfer
            ->setSku($firstProductEntity->getSku())
            ->setGiftCardProductConfiguration($giftCardProductConfigurationTransfer);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfiguration> $giftCardProductConfigurationEntities
     *
     * @return array<\Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer>
     */
    public function mapGiftCardProductConfigurationEntitiesToGiftCardProductConfigurationForProductTransfers(
        ObjectCollection $giftCardProductConfigurationEntities
    ): array {
        $giftCardProductConfigurationForProductTransfers = [];

        foreach ($giftCardProductConfigurationEntities as $giftCardProductConfigurationEntity) {
            $giftCardProductConfigurationForProductTransfers[] = $this->mapGiftCardProductConfigurationEntityToGiftCardProductConfigurationForProductTransfer(
                $giftCardProductConfigurationEntity,
                new GiftCardProductConfigurationForProductTransfer(),
            );
        }

        return $giftCardProductConfigurationForProductTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard> $paymentGiftCardEntities
     * @param list<\Generated\Shared\Transfer\PaymentGiftCardTransfer> $paymentGiftCardTransfers
     *
     * @return list<\Generated\Shared\Transfer\PaymentGiftCardTransfer>
     */
    public function mapPaymentGiftCardEntitiesToPaymentGiftCardTransfers(Collection $paymentGiftCardEntities, array $paymentGiftCardTransfers): array
    {
        foreach ($paymentGiftCardEntities as $paymentGiftCardEntity) {
            $paymentGiftCardTransfers[] = $this->mapPaymentGiftCardEntityToPaymentGiftCardTransfer($paymentGiftCardEntity, new PaymentGiftCardTransfer());
        }

        return $paymentGiftCardTransfers;
    }

    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard $paymentGiftCardEntity
     * @param \Generated\Shared\Transfer\PaymentGiftCardTransfer $paymentGiftCardTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentGiftCardTransfer
     */
    protected function mapPaymentGiftCardEntityToPaymentGiftCardTransfer(
        SpyPaymentGiftCard $paymentGiftCardEntity,
        PaymentGiftCardTransfer $paymentGiftCardTransfer
    ): PaymentGiftCardTransfer {
        return $paymentGiftCardTransfer->fromArray($paymentGiftCardEntity->toArray(), true);
    }
}
