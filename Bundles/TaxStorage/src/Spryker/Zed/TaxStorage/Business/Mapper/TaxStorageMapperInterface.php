<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\Mapper;

use Generated\Shared\Transfer\TaxRateStorageTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxRate;

interface TaxStorageMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate[] $spyTaxRates
     *
     * @return \Generated\Shared\Transfer\TaxRateStorageTransfer[]
     */
    public function mapSpyTaxRatesToTransfer(iterable $spyTaxRates): iterable;

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $spyTaxRate
     * @param \Generated\Shared\Transfer\TaxRateStorageTransfer $taxRateStorageTransfer
     *
     * @return \Generated\Shared\Transfer\TaxRateStorageTransfer
     */
    public function mapSpyTaxRateToTaxRateStorageTransfer(SpyTaxRate $spyTaxRate, TaxRateStorageTransfer $taxRateStorageTransfer): TaxRateStorageTransfer;
}
