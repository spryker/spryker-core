<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Filter;

interface ConcreteProductsResourceFilterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function filterConcreteProductResources(array $glueResourceTransfers): array;
}
