<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder;

interface ProductOptionRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     * @param array $resourceMapping
     * @param string $parentResourceType
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function createProductOptionRestResources(
        array $productAbstractOptionStorageTransfers,
        array $resourceMapping,
        string $parentResourceType,
        array $sorts
    ): array;
}
