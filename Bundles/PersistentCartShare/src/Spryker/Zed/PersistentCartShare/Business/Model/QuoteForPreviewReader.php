<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business\Model;

use Generated\Shared\Transfer\QuotePreviewRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareCriteriaTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface;

class QuoteForPreviewReader
{
    /**
     * @var \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface
     */
    protected $resourceShareFacade;

    /**
     * @var \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface $resourceShareFacade
     * @param \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        PersistentCartShareFacadeInterface $persistentCartFacade,
        PersistentCartShareToResourceShareFacadeInterface $resourceShareFacade,
        PersistentCartShareToQuoteFacadeInterface $quoteFacade
    ) {
        $this->resourceShareFacade = $resourceShareFacade;
        $this->persistentCartFacade = $persistentCartFacade;
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
                ->setIsSuccessful(false);
        }

        $idQuote = $this->getIdQuoteFromResourceShareResponce($resourceShareResponseTransfer);
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($idQuote);

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

        $this->resourceShareFacade->getResourceShare($resourceShareCriteriaTransfer);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setUuid($quotePreviewRequestTransfer->getResourceShareUuid());

        return $this->resourceShareFacade->activateResourceShare($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return int
     */
    protected function getIdQuoteFromResourceShareResponce(ResourceShareResponseTransfer $resourceShareResponseTransfer): int
    {
        $persistentCartShareResourceDataTransfer = $this->persistentCartFacade
            ->mapResourceDataToResourceDataTransfer($resourceShareResponseTransfer->getResourceShare());

        return $persistentCartShareResourceDataTransfer->requireIdQuote()->getIdQuote();
    }
}
