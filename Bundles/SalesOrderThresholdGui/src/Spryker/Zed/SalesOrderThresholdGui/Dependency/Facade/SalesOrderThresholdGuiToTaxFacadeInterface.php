<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade;

interface SalesOrderThresholdGuiToTaxFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets();
}
