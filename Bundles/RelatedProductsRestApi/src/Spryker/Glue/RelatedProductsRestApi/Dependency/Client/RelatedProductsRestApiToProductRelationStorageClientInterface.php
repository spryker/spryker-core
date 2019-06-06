<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi\Dependency\Client;

interface RelatedProductsRestApiToProductRelationStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findRelatedAbstractProductIds(int $idProductAbstract): array;
}
