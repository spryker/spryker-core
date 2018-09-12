<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestProductTaxRateTransfer;
use Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer;
use Generated\Shared\Transfer\TaxRateSetTransfer;

class ProductTaxSetsResourceMapper implements ProductTaxSetsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxRateSetTransfer $taxRateSetTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer
     */
    public function mapTaxRateSetTransferToRestTaxSetsAttributesTransfer(TaxRateSetTransfer $taxRateSetTransfer): RestProductTaxSetsAttributesTransfer
    {
        $restTaxSetTransfer = (new RestProductTaxSetsAttributesTransfer())->fromArray($taxRateSetTransfer->toArray(), true);
        foreach ($taxRateSetTransfer->getTaxRateSetItems() as $taxRateSetItem) {
            $restTaxSetTransfer->addRestProductTaxRate((new RestProductTaxRateTransfer())->fromArray($taxRateSetItem->toArray(), true));
        }

        return $restTaxSetTransfer;
    }
}
