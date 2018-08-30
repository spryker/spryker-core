<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

interface QuoteUuidWriterInterface
{
    /**
     * @return void
     */
    public function updateQuotesUuid(): void;
}
