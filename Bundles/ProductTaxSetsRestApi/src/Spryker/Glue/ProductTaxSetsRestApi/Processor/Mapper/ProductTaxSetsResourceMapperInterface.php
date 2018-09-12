<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

interface ProductTaxSetsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer
     */
    public function mapTaxSetTransferToRestTaxSetsAttributesTransfer(TaxSetTransfer $taxSetTransfer): RestProductTaxSetsAttributesTransfer;
}
