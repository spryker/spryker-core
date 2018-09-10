<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestTaxRateTransfer;
use Generated\Shared\Transfer\RestTaxSetsAttributesTransfer;
use Generated\Shared\Transfer\TaxRateSetTransfer;

class TaxSetsResourceMapper implements TaxSetsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxRateSetTransfer $taxRateSetTransfer
     *
     * @return \Generated\Shared\Transfer\RestTaxSetsAttributesTransfer
     */
    public function mapTaxRateSetTransferToRestTaxSetsAttributesTransfer(TaxRateSetTransfer $taxRateSetTransfer): RestTaxSetsAttributesTransfer
    {
        $restTaxSetTransfer = (new RestTaxSetsAttributesTransfer())->fromArray($taxRateSetTransfer->toArray(), true);
        foreach ($taxRateSetTransfer->getTaxRateSetItems() as $taxRateSetItem) {
            $restTaxSetTransfer->addRestTaxRate((new RestTaxRateTransfer())->fromArray($taxRateSetItem->toArray(), true));
        }

        return $restTaxSetTransfer;
    }
}
