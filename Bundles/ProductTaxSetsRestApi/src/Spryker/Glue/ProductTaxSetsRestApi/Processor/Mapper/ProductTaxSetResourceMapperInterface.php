<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;

interface ProductTaxSetResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxSetStorageTransfer $taxSetStorageTransfer
     * @param \Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer $restProductTaxSetsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer
     */
    public function mapTaxSetStorageTransferToRestProductTaxSetsAttributesTransfer(
        TaxSetStorageTransfer $taxSetStorageTransfer,
        RestProductTaxSetsAttributesTransfer $restProductTaxSetsAttributesTransfer
    ): RestProductTaxSetsAttributesTransfer;
}
