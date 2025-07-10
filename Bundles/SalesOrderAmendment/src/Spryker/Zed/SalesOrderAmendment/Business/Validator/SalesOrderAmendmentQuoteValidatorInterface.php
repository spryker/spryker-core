<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer;

interface SalesOrderAmendmentQuoteValidatorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer> $salesOrderAmendmentQuoteTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    public function validate(
        ArrayObject $salesOrderAmendmentQuoteTransfers
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer;
}
