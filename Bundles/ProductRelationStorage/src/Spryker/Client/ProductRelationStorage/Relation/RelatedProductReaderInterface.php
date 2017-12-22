<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Relation;

use Generated\Shared\Transfer\ProductViewTransfer;

interface RelatedProductReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return ProductViewTransfer[]
     */
    public function findRelatedProducts($idProductAbstract, $localeName);
}
