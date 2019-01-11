<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\SpyQuoteRequestVersionEntityTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion;

interface QuoteRequestVersionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyQuoteRequestVersionEntityTransfer $quoteRequestVersionEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function mapQuoteRequestVersionEntityToQuoteRequestVersionTransfer(
        SpyQuoteRequestVersionEntityTransfer $quoteRequestVersionEntityTransfer,
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer
    ): QuoteRequestVersionTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersionEntity
     *
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion
     */
    public function mapQuoteRequestVersionTransferToQuoteRequestVersionEntity(
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer,
        SpyQuoteRequestVersion $quoteRequestVersionEntity
    ): SpyQuoteRequestVersion;
}
