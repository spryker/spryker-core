<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

interface ProductAbstractRelationWriterInterface
{
    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    public function addRelations($idProductLabel, array $idsProductAbstract, bool $isTouchEnabled = true);
}
