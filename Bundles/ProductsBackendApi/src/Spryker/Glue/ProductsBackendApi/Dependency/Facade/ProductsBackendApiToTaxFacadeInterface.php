<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\TaxSetCollectionTransfer;
use Generated\Shared\Transfer\TaxSetCriteriaTransfer;

interface ProductsBackendApiToTaxFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxSetCriteriaTransfer $taxSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSetCollection(TaxSetCriteriaTransfer $taxSetCriteriaTransfer): TaxSetCollectionTransfer;
}
