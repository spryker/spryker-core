<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Saver;

use Generated\Shared\Transfer\QuoteTransfer;

interface SalesOrderItemProductClassesSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemProductClassesFromQuote(QuoteTransfer $quoteTransfer): void;
}
