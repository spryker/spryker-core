<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Reader;

interface ProductConcreteProductImageSetResourceRelationshipReaderInterface
{
    /**
     * @param list<string> $productConcreteSkus
     * @param string|null $localeName
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getProductImageSetRelationshipsIndexedBySku(array $productConcreteSkus, ?string $localeName): array;
}
