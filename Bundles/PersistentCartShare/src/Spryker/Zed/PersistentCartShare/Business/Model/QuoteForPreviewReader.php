<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuotePreviewRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareCriteriaTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface;

class QuoteForPreviewReader implements QuoteForPreviewReaderInterface
{
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
     * @param \Generated\Shared\Transfer\QuotePreviewRequestTransfer $quotePreviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(QuotePreviewRequestTransfer $quotePreviewRequestTransfer): QuoteResponseTransfer
    {
        $resourceShareResponseTransfer = $this->getResourceShare($quotePreviewRequestTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false)
                ->setErrors(
                    $this->convertMessagesToQuoteErrors($resourceShareResponseTransfer->getErrorMessages())
                );
        }

        $resourceShareResponseTransfer = $this->activateResourceShare($quotePreviewRequestTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false)
                ->setErrors(
                    $this->convertMessagesToQuoteErrors($resourceShareResponseTransfer->getErrorMessages())
                );
        }

        $quoteResponseTransfer = $this->quoteFacade->findQuoteById(
            $this->getIdQuoteFromResourceShareResponce($resourceShareResponseTransfer)
        );

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePreviewRequestTransfer $quotePreviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShare(QuotePreviewRequestTransfer $quotePreviewRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareCriteriaTransfer = (new ResourceShareCriteriaTransfer())
            ->setUuid($quotePreviewRequestTransfer->getResourceShareUuid());

        return $this->resourceShareFacade->getResourceShare($resourceShareCriteriaTransfer);
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
     * @param \Generated\Shared\Transfer\QuotePreviewRequestTransfer $quotePreviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function activateResourceShare(QuotePreviewRequestTransfer $quotePreviewRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setUuid($quotePreviewRequestTransfer->getResourceShareUuid());

        return $this->resourceShareFacade->activateResourceShare($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $errorMessages
     *
     * @return \Generated\Shared\Transfer\QuoteErrorTransfer[]
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
}
