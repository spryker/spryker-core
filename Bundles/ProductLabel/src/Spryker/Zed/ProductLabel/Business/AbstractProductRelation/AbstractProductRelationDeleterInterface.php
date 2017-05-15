<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\AbstractProductRelation;

interface AbstractProductRelationDeleterInterface
{

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteRelationsForLabel($idProductLabel);

}
