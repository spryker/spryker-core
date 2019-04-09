<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Reader;

use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteRequestReaderInterface
{
    /**
     * @param string $quoteRequestReference
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findCompanyUserQuoteRequestByReference(string $quoteRequestReference, int $idCompanyUser): ?QuoteRequestTransfer;
}
