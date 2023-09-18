<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Config;

use Generated\Shared\Transfer\TaxAppConfigTransfer;

interface ConfigWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @return void
     */
    public function write(TaxAppConfigTransfer $taxAppConfigTransfer): void;
}
