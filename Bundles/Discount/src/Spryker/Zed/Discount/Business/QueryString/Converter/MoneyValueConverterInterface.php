<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Converter;

use Generated\Shared\Transfer\ClauseTransfer;

interface MoneyValueConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return void
     */
    public function convertDecimalToCent(ClauseTransfer $clauseTransfer);
}
