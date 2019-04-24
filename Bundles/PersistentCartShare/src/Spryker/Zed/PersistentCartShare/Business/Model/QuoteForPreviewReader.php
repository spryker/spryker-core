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

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false)
                ->setErrors(
                    $this->convertMessagesToQuoteErrors($resourceShareResponseTransfer->getMessages())
                );
        }

        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();
        if ($resourceShareTransfer->getResourceType() !== static::RESOURCE_TYPE_QUOTE) { //todo do we need this double check?
            return $this->createErrorResponse(static::GLOSSARY_KEY_PERSISTENT_CART_SHARE_RESOURCE_TYPE_MISMATCH);
        }

        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();
        if ($resourceShareDataTransfer->getShareOption() !== static::RESOURCE_SHARE_OPTION_PREVIEW) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR);
        }

        $quoteResponseTransfer = $this->quoteFacade->findQuoteById(
            $resourceShareDataTransfer->requireIdQuote()->getIdQuote()
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE);
        }

        return $quoteResponseTransfer;
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
