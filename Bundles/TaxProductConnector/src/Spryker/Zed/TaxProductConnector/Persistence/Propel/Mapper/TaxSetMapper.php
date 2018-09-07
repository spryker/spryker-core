<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\TaxRateSetItemTransfer;
use Generated\Shared\Transfer\TaxRateSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxSet;

class TaxSetMapper implements TaxSetMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     *
     * @return \Generated\Shared\Transfer\TaxRateSetTransfer
     */
    public function mapTaxSetToTransfer(SpyTaxSet $taxSetEntity): TaxRateSetTransfer
    {
        $taxRateSetTransfer = new TaxRateSetTransfer();
        $taxRates = $taxSetEntity->getSpyTaxRates();
        $taxRateSetTransfer->setName($taxSetEntity->getName());
        $taxRateSetTransfer->setUuid($taxSetEntity->getUuid());
        foreach ($taxRates as $taxRate) {
            $taxRateSetItemTransfer = (new TaxRateSetItemTransfer())->fromArray($taxRate->toArray(), true);
            if ($taxRate->getCountry()) {
                $taxRateSetItemTransfer->setCountry($taxRate->getCountry()->getIso2Code());
            }
            $taxRateSetTransfer->addTaxRateSetItem($taxRateSetItemTransfer);
        }

        return $taxRateSetTransfer;
    }
}
