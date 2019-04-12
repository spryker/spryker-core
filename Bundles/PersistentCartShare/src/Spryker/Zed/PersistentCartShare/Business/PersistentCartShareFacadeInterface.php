<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business;

use Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer;
use Generated\Shared\Transfer\QuotePreviewRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

/**
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareBusinessFactory getFactory()
 */
interface PersistentCartShareFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuotePreviewRequestTransfer $quotePreviewRequestTransfer
     *
     * @return mixed
     */
    public function getQuoteForPreview(QuotePreviewRequestTransfer $quotePreviewRequestTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer
     */
    public function getResourceDataFromResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): PersistentCartShareResourceDataTransfer;
}
