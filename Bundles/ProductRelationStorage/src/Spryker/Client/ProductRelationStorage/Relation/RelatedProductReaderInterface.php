<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Relation;

interface RelatedProductReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findRelatedProducts($idProductAbstract, $localeName, string $storeName);

    /**
     * @param int $idProductAbstract
     * @param string|null $storeName
     *
     * @return int[]
     */
    public function findRelatedAbstractProductIds(int $idProductAbstract, ?string $storeName = null): array;
}
