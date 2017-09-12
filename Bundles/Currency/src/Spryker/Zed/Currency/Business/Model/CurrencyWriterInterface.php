<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Model;

use Generated\Shared\Transfer\CurrencyTransfer;

interface CurrencyWriterInterface
{

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    public function create(CurrencyTransfer $currencyTransfer);

}
