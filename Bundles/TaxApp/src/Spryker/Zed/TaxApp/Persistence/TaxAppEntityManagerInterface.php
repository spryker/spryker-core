<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;

interface TaxAppEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function saveTaxAppConfig(TaxAppConfigTransfer $taxAppConfigTransfer, StoreTransfer $storeTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return void
     */
    public function deleteTaxAppConfig(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): void;
}
