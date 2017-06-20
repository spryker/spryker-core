<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

interface ProductAbstractRelationDeleterInterface
{

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    public function removeRelations($idProductLabel, array $idsProductAbstract);

}
