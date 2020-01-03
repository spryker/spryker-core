<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Cleaner;

interface QuoteRequestCleanerInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function deleteQuoteRequestsByIdCompanyUser(int $idCompanyUser): void;
}
