<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\ResourceShare;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\MultiCart\CartOperation\CartUpdaterInterface;

class SwitchDefaultCartResourceShareActivatorStrategy implements SwitchDefaultCartResourceShareActivatorStrategyInterface
{
    /**
     * @var \Spryker\Client\MultiCart\CartOperation\CartUpdaterInterface
     */
    protected $cartUpdater;

    /**
     * @param \Spryker\Client\MultiCart\CartOperation\CartUpdaterInterface $cartUpdater
     */
    public function __construct(CartUpdaterInterface $cartUpdater)
    {
        $this->cartUpdater = $cartUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function applySwitchDefaultCartResourceShareActivatorStrategy(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareDataTransfer = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData();

        $resourceShareDataTransfer->requireIdQuote();
        $quoteTransfer = (new QuoteTransfer())
            ->setIdQuote($resourceShareDataTransfer->getIdQuote());

        return $this->updateDefaultQuote($quoteTransfer, $resourceShareRequestTransfer->getResourceShare());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function updateDefaultQuote(
        QuoteTransfer $quoteTransfer,
        ResourceShareTransfer $resourceShareTransfer
    ): ResourceShareResponseTransfer {
        $quoteResponseTransfer = $this->cartUpdater->setDefaultQuote($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessages(
                    $this->mapQuoteErrorTransfersToMessageTransfers($quoteResponseTransfer->getErrors())
                );
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\QuoteErrorTransfer[] $quoteErrorTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function mapQuoteErrorTransfersToMessageTransfers(ArrayObject $quoteErrorTransfers): ArrayObject
    {
        $messageTransfers = new ArrayObject();
        foreach ($quoteErrorTransfers as $quoteErrorTransfer) {
            $messageTransfers->append(
                (new MessageTransfer())->setValue($quoteErrorTransfer->getMessage())
            );
        }

        return $messageTransfers;
    }
}
