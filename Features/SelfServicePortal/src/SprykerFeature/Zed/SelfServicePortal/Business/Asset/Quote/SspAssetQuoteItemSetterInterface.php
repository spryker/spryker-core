<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;

interface SspAssetQuoteItemSetterInterface
{
    public function setSspAssetToQuoteItem(SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer): QuoteResponseTransfer;
}
