<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business;

use Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer;
use Generated\Shared\Transfer\QuotePreviewRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareBusinessFactory getFactory()
 */
class PersistentCartShareFacade extends AbstractFacade implements PersistentCartShareFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuotePreviewRequestTransfer $quotePreviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(QuotePreviewRequestTransfer $quotePreviewRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteForPreviewReader()
            ->getQuoteForPreview($quotePreviewRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer
     */
    public function getResourceDataFromResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): PersistentCartShareResourceDataTransfer
    {
        return $this->getFactory()
            ->createResourceDataReader()
            ->getResourceDataFromResourceShareTransfer($resourceShareTransfer);
    }
}
