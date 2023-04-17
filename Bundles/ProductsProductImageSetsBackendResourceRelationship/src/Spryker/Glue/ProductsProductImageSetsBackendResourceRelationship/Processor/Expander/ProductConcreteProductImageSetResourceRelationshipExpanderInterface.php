<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface ProductConcreteProductImageSetResourceRelationshipExpanderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addProductConcreteProductImageSetsRelationships(array $glueResourceTransfers, GlueRequestTransfer $glueRequestTransfer): void;
}
