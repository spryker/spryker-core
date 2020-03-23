<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\GiftCardMetadataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface;

class MetadataExpander implements MetadataExpanderInterface
{
    protected const SKU_ABSTRACT = 'SKU_ABSTRACT';
    protected const SKU_CONCRETE = 'SKU_CONCRETE';

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface
     */
    protected $giftCardReader;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     */
    public function __construct(GiftCardReaderInterface $giftCardReader)
    {
        $this->giftCardReader = $giftCardReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandGiftCardMetadata(CartChangeTransfer $cartChangeTransfer)
    {
        $skus = $this->getAbstractAndConcreteSkusFromCartChangeTransfer($cartChangeTransfer);
        $abstractConfiguration = $this
            ->giftCardReader
            ->getGiftCardAbstractConfigurationsForProductAbstractByAbstractSkus($skus[static::SKU_ABSTRACT]);
        $indexedGiftCardAbstractConfigurationForAbstractProductTransfers = $this
            ->indexGiftCardAbstractConfigurationForProductAbstractTransfersByAbstractSku($abstractConfiguration);

        $concreteConfiguration = $this
            ->giftCardReader
            ->getGiftCardConcreteConfigurationsForProductConcreteByConcreteSkus($skus[static::SKU_CONCRETE]);
        $indexedGiftCartConfigurationForProductTransfers = $this->indexGiftCardConfigurationForProductTransfersBySku($concreteConfiguration);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $giftCardMetadata = $this->getGiftCardMetadata(
                $itemTransfer,
                $indexedGiftCardAbstractConfigurationForAbstractProductTransfers,
                $indexedGiftCartConfigurationForProductTransfers
            );
            $itemTransfer->setGiftCardMetadata($giftCardMetadata);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer[] $indexedGiftCardAbstractConfigurationForAbstractProductTransfers
     * @param \Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer[] $indexedGiftCartConfigurationForProductTransfers
     *
     * @return \Generated\Shared\Transfer\GiftCardMetadataTransfer
     */
    protected function getGiftCardMetadata(
        ItemTransfer $itemTransfer,
        array $indexedGiftCardAbstractConfigurationForAbstractProductTransfers,
        array $indexedGiftCartConfigurationForProductTransfers
    ) {
        $itemTransfer->requireAbstractSku();
        $metadata = new GiftCardMetadataTransfer();

        $abstractGiftCardConfiguration = $indexedGiftCardAbstractConfigurationForAbstractProductTransfers[$itemTransfer->getAbstractSku()] ?? null;

        if (!$abstractGiftCardConfiguration) {
            $metadata->setIsGiftCard(false);

            return $metadata;
        }

        $metadata->setIsGiftCard(true);
        $metadata->setAbstractConfiguration($abstractGiftCardConfiguration->getGiftCardAbstractProductConfiguration());

        $concreteConfiguration = $indexedGiftCartConfigurationForProductTransfers[$itemTransfer->getSku()];
        $metadata->setConcreteConfiguration($concreteConfiguration->getGiftCardProductConfiguration());

        return $metadata;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[][]
     */
    protected function getAbstractAndConcreteSkusFromCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [
            static::SKU_ABSTRACT => [],
            static::SKU_CONCRETE => [],
        ];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $skus[static::SKU_ABSTRACT][] = $itemTransfer->getAbstractSku();
            $skus[static::SKU_CONCRETE][] = $itemTransfer->getSku();
        }

        return $skus;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer[] $giftCardAbstractConfigurationForProductAbstractTransfers
     *
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer[]
     */
    protected function indexGiftCardAbstractConfigurationForProductAbstractTransfersByAbstractSku(
        array $giftCardAbstractConfigurationForProductAbstractTransfers
    ): array {
        $indexedGiftCardAbstractConfigurationForProductAbstractTransfers = [];

        foreach ($giftCardAbstractConfigurationForProductAbstractTransfers as $giftCardAbstractProductConfigurationForProductAbstractTransfer) {
            $indexedGiftCardAbstractConfigurationForProductAbstractTransfers[$giftCardAbstractProductConfigurationForProductAbstractTransfer->getAbstractSku()]
                = $giftCardAbstractProductConfigurationForProductAbstractTransfer;
        }

        return $indexedGiftCardAbstractConfigurationForProductAbstractTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer[] $giftCartConfigurationForProductTransfers
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer[]
     */
    protected function indexGiftCardConfigurationForProductTransfersBySku(array $giftCartConfigurationForProductTransfers): array
    {
        $indexedGiftCartConfigurationForProductTransfers = [];

        foreach ($giftCartConfigurationForProductTransfers as $giftCardProductConfigurationForProductTransfer) {
            $indexedGiftCartConfigurationForProductTransfers[$giftCardProductConfigurationForProductTransfer->getSku()]
                = $giftCardProductConfigurationForProductTransfer;
        }

        return $indexedGiftCartConfigurationForProductTransfers;
    }
}
