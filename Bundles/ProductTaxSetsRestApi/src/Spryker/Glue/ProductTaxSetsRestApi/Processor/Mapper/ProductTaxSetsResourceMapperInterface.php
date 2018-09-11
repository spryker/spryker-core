<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestTaxSetsAttributesTransfer;
use Generated\Shared\Transfer\TaxRateSetTransfer;

interface ProductTaxSetsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxRateSetTransfer $taxRateSetTransfer
     *
     * @return \Generated\Shared\Transfer\RestTaxSetsAttributesTransfer
     */
    public function mapTaxRateSetTransferToRestTaxSetsAttributesTransfer(TaxRateSetTransfer $taxRateSetTransfer): RestTaxSetsAttributesTransfer;
}
