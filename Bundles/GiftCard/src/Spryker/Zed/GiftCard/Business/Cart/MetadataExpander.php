<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\GiftCardMetadataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface;

class MetadataExpander implements MetadataExpanderInterface
{

    /**
     * @var \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface
     */
    protected $giftCardQueryContainer;

    /**
     * @param \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface $giftCardQueryContainer
     */
    public function __construct(GiftCardQueryContainerInterface $giftCardQueryContainer)
    {
        $this->giftCardQueryContainer = $giftCardQueryContainer;
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

        $giftCardConfiguration = $this->findGiftCardConfiguration($itemTransfer->getAbstractSku());

        if (!$giftCardConfiguration) {
            $metadata->setIsGiftCard(false);

            return $metadata;
        }

        $metadata->setIsGiftCard(true);

        return $metadata;
    }

    /**
     * @param string $abstractSku
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfiguration|null
     */
    protected function findGiftCardConfiguration($abstractSku)
    {
        return $this->giftCardQueryContainer->queryGiftCardConfigurationByProductAbstractSku($abstractSku)->findOne();
    }

}
