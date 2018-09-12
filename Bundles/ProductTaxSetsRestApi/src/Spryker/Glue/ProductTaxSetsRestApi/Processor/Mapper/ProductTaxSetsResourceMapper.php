<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestProductTaxRateTransfer;
use Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

class ProductTaxSetsResourceMapper implements ProductTaxSetsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxRateSetTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer
     */
    public function mapTaxSetTransferToRestTaxSetsAttributesTransfer(TaxSetTransfer $taxRateSetTransfer): RestProductTaxSetsAttributesTransfer
    {
        $restTaxSetTransfer = (new RestProductTaxSetsAttributesTransfer())->fromArray($taxRateSetTransfer->toArray(), true);
        foreach ($taxRateSetTransfer->getTaxRates() as $taxRate) {
            $restProductTaxRateTransfer = (new RestProductTaxRateTransfer())->fromArray($taxRate->toArray(), true);
            if ($taxRate->getCountry()) {
                $restProductTaxRateTransfer->setCountry($taxRate->getCountry()->getIso2Code());
            }
            $restTaxSetTransfer->addRestProductTaxRate($restProductTaxRateTransfer);
        }

        return $restTaxSetTransfer;
    }
}
