<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface ConcreteProductResourceRelationshipReaderInterface
{
    /**
     * @param array<int, string> $productConcreteSkus
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getConcreteProductRelationshipsIndexedBySku(array $productConcreteSkus, GlueRequestTransfer $glueRequestTransfer): array;
}
