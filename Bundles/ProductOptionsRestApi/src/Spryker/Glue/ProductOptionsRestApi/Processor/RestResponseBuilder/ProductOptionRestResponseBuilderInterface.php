<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;

interface ProductOptionRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     * @param string $parentResourceType
     * @param string $parentResourceId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function buildProductOptionRestResources(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer,
        string $parentResourceType,
        string $parentResourceId,
        array $sorts
    ): array;
}
