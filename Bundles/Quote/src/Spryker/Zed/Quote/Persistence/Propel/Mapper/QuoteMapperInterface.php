<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Orm\Zed\Quote\Persistence\SpyQuote;

interface QuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Quote\Persistence\SpyQuote $quoteEntity
     * @param array $quoteFieldsAllowedForSaving
     *
     * @return \Orm\Zed\Quote\Persistence\SpyQuote
     */
    public function mapTransferToEntity(QuoteTransfer $quoteTransfer, SpyQuote $quoteEntity, array $quoteFieldsAllowedForSaving): SpyQuote;
}
