<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Asset\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;

interface QuoteStorageStrategyInterface
{
    public function attachSspAssetToQuoteItem(SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer): QuoteResponseTransfer;

    public function getStorageStrategy(): string;
}
