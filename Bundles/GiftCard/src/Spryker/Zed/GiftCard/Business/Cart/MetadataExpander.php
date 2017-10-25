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
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setGiftCardMetadata($this->getGiftCardMetadata($itemTransfer));
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardMetadataTransfer
     */
    protected function getGiftCardMetadata(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireAbstractSku();
        $metadata = new GiftCardMetadataTransfer();

        $abstractGiftCardConfiguration = $this->giftCardReader->findGiftCardAbstractConfiguration($itemTransfer->getAbstractSku());

        if (!$abstractGiftCardConfiguration) {
            $metadata->setIsGiftCard(false);

            return $metadata;
        }

        $metadata->setIsGiftCard(true);
        $metadata->setAbstractConfiguration($abstractGiftCardConfiguration);

        $concreteConfiguration = $this->giftCardReader->findGiftCardConcreteConfiguration($itemTransfer->getSku());
        $metadata->setConcreteConfiguration($concreteConfiguration);

        return $metadata;
    }
}
