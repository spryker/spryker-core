<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteReaderInterface
{
    /**
     * @param string $resourceShareUuid
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(string $resourceShareUuid): QuoteResponseTransfer;
}
