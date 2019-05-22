<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business\Quote;

use ArrayObject;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Shared\PersistentCartShare\PersistentCartShareConfig;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface;

class QuoteReader implements QuoteReaderInterface
{
    protected const GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE = 'persistent_cart_share.error.quote_is_not_available';
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE = 'persistent_cart_share.error.resource_is_not_available';

    /**
     * @var \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface
     */
    protected $resourceShareFacade;

    /**
     * @var \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface $resourceShareFacade
     * @param \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        PersistentCartShareToResourceShareFacadeInterface $resourceShareFacade,
        PersistentCartShareToQuoteFacadeInterface $quoteFacade
    ) {
        $this->resourceShareFacade = $resourceShareFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteByResourceShareUuid(ResourceShareRequestTransfer $resourceShareRequestTransfer): QuoteResponseTransfer
    {
        $resourceShareResponseTransfer = $this->resourceShareFacade->getResourceShareByUuid($resourceShareRequestTransfer);

        $quoteResponseTransfer = $this->validateResourceShareResponse($resourceShareResponseTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $quoteResponseTransfer = $this->quoteFacade->findQuoteById(
            $resourceShareDataTransfer->requireIdQuote()
                ->getIdQuote()
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteResponseTransferWithQuoteError(static::GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE);
        }

        if ($quoteResponseTransfer->requireQuoteTransfer()->getQuoteTransfer()->getIsLocked()) {
            return $this->createQuoteResponseTransferWithQuoteError(static::GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function validateResourceShareResponse(ResourceShareResponseTransfer $resourceShareResponseTransfer): QuoteResponseTransfer
    {
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false)
                ->setErrors(
                    $this->mapResourceShareResponseMessagesToQuoteResponseTransferErrors($resourceShareResponseTransfer->getMessages())
                );
        }

        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();
        if ($resourceShareTransfer->getResourceType() !== PersistentCartShareConfig::RESOURCE_TYPE_QUOTE) {
            return $this->createQuoteResponseTransferWithQuoteError(static::GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE);
        }

        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();
        if ($resourceShareDataTransfer->getShareOption() !== PersistentCartShareConfig::SHARE_OPTION_PREVIEW) {
            return $this->createQuoteResponseTransferWithQuoteError(static::GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE);
        }

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $resourceShareResponseMessages
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\QuoteErrorTransfer[]
     */
    protected function mapResourceShareResponseMessagesToQuoteResponseTransferErrors(ArrayObject $resourceShareResponseMessages): ArrayObject
    {
        $quoteResponseTransferErrors = new ArrayObject();
        foreach ($resourceShareResponseMessages as $resourceShareResponseMessageTransfer) {
            $quoteResponseTransferErrors->append(
                (new QuoteErrorTransfer())->setMessage($resourceShareResponseMessageTransfer->getValue())
            );
        }

        return $quoteResponseTransferErrors;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteResponseTransferWithQuoteError(string $message): QuoteResponseTransfer
    {
        $quoteErrorTransfers = new ArrayObject();

        $quoteErrorTransfers->append(
            (new QuoteErrorTransfer())->setMessage($message)
        );

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->setErrors($quoteErrorTransfers);
    }
}
