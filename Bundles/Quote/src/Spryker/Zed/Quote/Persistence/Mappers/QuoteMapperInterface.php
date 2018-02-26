<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence\Mappers;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;

interface QuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapEntityTransferToTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpyQuoteEntityTransfer
     */
    public function mapTransferToEntityTransfer(QuoteTransfer $quoteTransfer): SpyQuoteEntityTransfer;
}
