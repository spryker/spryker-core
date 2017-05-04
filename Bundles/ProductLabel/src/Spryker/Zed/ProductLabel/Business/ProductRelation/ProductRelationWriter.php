<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductRelation;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract;

class ProductRelationWriter implements ProductRelationWriterInterface
{

    /**
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function setRelation($idProductLabel, $idProductAbstract)
    {
        $relationEntity = new SpyProductLabelProductAbstract();
        $relationEntity->setFkProductLabel($idProductLabel);
        $relationEntity->setFkProductAbstract($idProductAbstract);
        $relationEntity->save();
    }

}
