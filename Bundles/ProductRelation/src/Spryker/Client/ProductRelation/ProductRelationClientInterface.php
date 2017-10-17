<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation;

/**
 * @method \Spryker\Client\ProductRelation\ProductRelationFactory getFactory()
 */
interface ProductRelationClientInterface
{
    /**
     * Specification:
     *   - Reads abstract product relations
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageProductRelationsTransfer[]
     */
    public function getProductRelationsByIdProductAbstract($idProductAbstract);
}
