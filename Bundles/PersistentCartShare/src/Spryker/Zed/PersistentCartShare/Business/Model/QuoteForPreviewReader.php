<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Shared\PersistentCartShare\PersistentCartShareConstants;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface;

class QuoteForPreviewReader implements QuoteForPreviewReaderInterface
{
    /**
     * @see \Spryker\Zed\PersistentCart\Business\Model\QuoteResolver::GLOSSARY_KEY_QUOTE_NOT_AVAILABLE
     */
    protected const GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';
    protected const GLOSSARY_KEY_PERSISTENT_CART_SHARE_INVALID_RESOURCE_ERROR = 'persistent_cart_share.invalid-resource.error';

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
    public function getQuoteForPreview(ResourceShareRequestTransfer $resourceShareRequestTransfer): QuoteResponseTransfer
    {
        $resourceShareResponseTransfer = $this->resourceShareFacade
            ->getResourceShareByUuid($resourceShareRequestTransfer);

        $quoteResponseTransfer = $this->validateResourceShareResponce($resourceShareResponseTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $quoteResponseTransfer = $this->quoteFacade->findQuoteById(
            $resourceShareDataTransfer->requireIdQuote()->getIdQuote()
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteResponseTransferWithError(static::GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteResponseTransferWithError(string $message): QuoteResponseTransfer
    {
        $quoteErrorTransferCollection = new ArrayObject();
        $quoteErrorTransferCollection->append(
            (new QuoteErrorTransfer())
                ->setMessage($message)
        );

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->setErrors($quoteErrorTransferCollection);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $errorMessages
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteResponseTransferWithErrorsFromMessages(ArrayObject $errorMessages): QuoteResponseTransfer
    {
        $quoteErrorTransferCollection = new ArrayObject();
        foreach ($errorMessages as $errorMessage) {
            $quoteErrorTransferCollection->append(
                (new QuoteErrorTransfer())
                    ->setMessage($errorMessage->getValue())
            );
        }

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->setErrors($quoteErrorTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function validateResourceShareResponce(ResourceShareResponseTransfer $resourceShareResponseTransfer): QuoteResponseTransfer
    {
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteResponseTransferWithErrorsFromMessages($resourceShareResponseTransfer->getMessages());
        }

        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();
        if ($resourceShareTransfer->getResourceType() !== PersistentCartShareConstants::RESOURCE_TYPE_QUOTE) {
            return $this->createQuoteResponseTransferWithError(static::GLOSSARY_KEY_PERSISTENT_CART_SHARE_INVALID_RESOURCE_ERROR);
        }

        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();
        if ($resourceShareDataTransfer->getShareOption() !== PersistentCartShareConstants::SHARE_OPTION_PREVIEW) {
            return $this->createQuoteResponseTransferWithError(static::GLOSSARY_KEY_PERSISTENT_CART_SHARE_INVALID_RESOURCE_ERROR);
        }

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(true);
    }
}
