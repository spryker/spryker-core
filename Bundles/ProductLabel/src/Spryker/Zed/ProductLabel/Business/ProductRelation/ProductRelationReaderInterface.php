<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductRelation;

interface ProductRelationReaderInterface
{

    /**
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function readForProductLabel($idProductLabel);

}
