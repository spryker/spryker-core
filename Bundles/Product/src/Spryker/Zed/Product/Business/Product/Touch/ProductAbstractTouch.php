<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Touch;

class ProductAbstractTouch extends AbstractProductTouch implements ProductAbstractTouchInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstract($idProductAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $this->touchAbstractByStatus($idProductAbstract);
        $this->touchVariantsByStatus($idProductAbstract);

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchVariantsByStatus($idProductAbstract)
    {
        $concreteProductCollection = $this->productQueryContainer
            ->queryProduct()
            ->findByFkProductAbstract($idProductAbstract);

        foreach ($concreteProductCollection as $productEntity) {
            $this->touchConcreteByStatus($productEntity);
        }
    }
}
