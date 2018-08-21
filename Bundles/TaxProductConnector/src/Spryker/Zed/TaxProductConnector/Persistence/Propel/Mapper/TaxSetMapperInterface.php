<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\TaxRateSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxSet;

interface TaxSetMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     *
     * @return \Generated\Shared\Transfer\TaxRateSetTransfer
     */
    public function mapTaxSetToTransfer(SpyTaxSet $taxSetEntity): TaxRateSetTransfer;
}
