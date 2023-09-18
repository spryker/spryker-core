<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Config;

use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;

interface ConfigDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return void
     */
    public function delete(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): void;
}
