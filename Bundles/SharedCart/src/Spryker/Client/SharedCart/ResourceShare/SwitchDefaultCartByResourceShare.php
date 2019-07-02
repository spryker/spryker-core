<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface;

class SwitchDefaultCartByResourceShare implements SwitchDefaultCartByResourceShareInterface
{
    protected const GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE = 'persistent_cart_share.error.quote_is_not_available';

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface $multiCartClient
     */
    public function __construct(SharedCartToMultiCartClientInterface $multiCartClient)
    {
        $this->multiCartClient = $multiCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function switchDefaultCartByResourceShare(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareDataTransfer = $resourceShareRequestTransfer
            ->getResourceShare()
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
        $quoteResponseTransfer = $this->multiCartClient->setDefaultQuote($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE)
                );
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }
}
