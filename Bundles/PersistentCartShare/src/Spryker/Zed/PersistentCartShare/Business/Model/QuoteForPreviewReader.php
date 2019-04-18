<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface;

class QuoteForPreviewReader implements QuoteForPreviewReaderInterface
{
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    protected const RESOURCE_SHARE_OPTION_PREVIEW = 'PREVIEW';

    /**
     * @see \Spryker\Zed\PersistentCart\Business\Model\QuoteResolver::GLOSSARY_KEY_QUOTE_NOT_AVAILABLE
     */
    protected const GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';
    protected const GLOSSARY_KEY_PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR = 'persistent_cart_share.quote.access_denied.error';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND
     */
    protected const GLOSSARY_KEY_PERSISTENT_CART_SHARE_RESOURCE_TYPE_MISMATCH = 'resource_share.reader.error.resource_is_not_found';

    /**
     * @var \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface
     */
    protected $resourceShareFacade;

    /**
     * @var \Spryker\Zed\PersistentCartShare\Business\Model\ResourceDataReaderInterface
     */
    protected $resourceDataMapper;

    /**
     * @var \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\PersistentCartShare\Business\Model\ResourceDataReaderInterface $resourceDataMapper
     * @param \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface $resourceShareFacade
     * @param \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        ResourceDataReaderInterface $resourceDataMapper,
        PersistentCartShareToResourceShareFacadeInterface $resourceShareFacade,
        PersistentCartShareToQuoteFacadeInterface $quoteFacade
    ) {
        $this->resourceShareFacade = $resourceShareFacade;
        $this->resourceDataMapper = $resourceDataMapper;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(ResourceShareTransfer $resourceShareTransfer): QuoteResponseTransfer
    {
        $resourceShareResponseTransfer = $this->resourceShareFacade
            ->getResourceShare(
                $resourceShareTransfer->setResourceType(self::RESOURCE_TYPE_QUOTE)
            );

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false)
                ->setErrors(
                    $this->convertMessagesToQuoteErrors($resourceShareResponseTransfer->getErrorMessages())
                );
        }

        if ($resourceShareResponseTransfer->getResourceShare()->getResourceType() !== static::RESOURCE_TYPE_QUOTE) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_PERSISTENT_CART_SHARE_RESOURCE_TYPE_MISMATCH);
        }

        if ($this->getShareOptionsFromResourceShareResponce($resourceShareResponseTransfer) !== static::RESOURCE_SHARE_OPTION_PREVIEW) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR);
        }

        $quoteResponseTransfer = $this->quoteFacade->findQuoteById(
            $this->getIdQuoteFromResourceShareResponce($resourceShareResponseTransfer)
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return int
     */
    protected function getIdQuoteFromResourceShareResponce(ResourceShareResponseTransfer $resourceShareResponseTransfer): int
    {
        $persistentCartShareResourceDataTransfer = $this->resourceDataMapper
            ->getResourceDataFromResourceShareTransfer(
                $resourceShareResponseTransfer->getResourceShare()
            );

        return $persistentCartShareResourceDataTransfer
            ->requireIdQuote()
            ->getIdQuote();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $errorMessages
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\QuoteErrorTransfer[]
     */
    protected function convertMessagesToQuoteErrors(ArrayObject $errorMessages): ArrayObject
    {
        $quoteErrorTransferCollection = new ArrayObject();
        foreach ($errorMessages as $errorMessage) {
            $quoteErrorTransferCollection->append(
                (new QuoteErrorTransfer())
                    ->setMessage(
                        $errorMessage->getValue()
                    )
            );
        }

        return $quoteErrorTransferCollection;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createTypeMismatchErrorResponse(): QuoteResponseTransfer
    {
        $quoteErrorTransferCollection = new ArrayObject();
        $quoteErrorTransferCollection->append(
            (new QuoteErrorTransfer())
                ->setMessage(
                    static::GLOSSARY_KEY_PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR
                )
        );

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->setErrors($quoteErrorTransferCollection);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteNotFoundErrorResponse(): QuoteResponseTransfer
    {
        $quoteErrorTransferCollection = new ArrayObject();
        $quoteErrorTransferCollection->append(
            (new QuoteErrorTransfer())
                ->setMessage(
                    static::GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE
                )
        );

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->setErrors($quoteErrorTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return string|null
     */
    protected function getShareOptionsFromResourceShareResponce(ResourceShareResponseTransfer $resourceShareResponseTransfer): ?string
    {
        $persistentCartShareResourceDataTransfer = $this->resourceDataMapper
            ->getResourceDataFromResourceShareTransfer(
                $resourceShareResponseTransfer->getResourceShare()
            );

        return $persistentCartShareResourceDataTransfer
            ->getShareOption();
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createErrorResponse(string $message): QuoteResponseTransfer
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
}
