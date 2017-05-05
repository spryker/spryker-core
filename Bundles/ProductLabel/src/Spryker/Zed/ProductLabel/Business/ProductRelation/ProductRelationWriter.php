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
        $relationEntity = $this->createRelationEntity($idProductLabel, $idProductAbstract);
        $relationEntity->save();
    }

    /**
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function removeRelation($idProductLabel, $idProductAbstract)
    {
        $relationEntity = $this->createRelationEntity($idProductLabel, $idProductAbstract);
        $relationEntity->delete();
    }

    /**
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract
     */
    protected function createRelationEntity($idProductLabel, $idProductAbstract)
    {
        $relationEntity = new SpyProductLabelProductAbstract();
        $relationEntity->setFkProductLabel($idProductLabel);
        $relationEntity->setFkProductAbstract($idProductAbstract);

        return $relationEntity;
    }

}
