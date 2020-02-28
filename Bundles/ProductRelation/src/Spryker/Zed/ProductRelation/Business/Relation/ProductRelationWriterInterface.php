<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation;

interface ProductRelationWriterInterface
{
    /**
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return bool
     */
    public function deleteProductRelation($idProductRelation);
}
