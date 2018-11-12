<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\TaxRateTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxRate;

interface TaxRateMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $taxRateEntity
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function mapTaxRateEntityToTaxRateTransfer(
        SpyTaxRate $taxRateEntity,
        TaxRateTransfer $taxRateTransfer
    ): TaxRateTransfer;
}
