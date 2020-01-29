<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Touch;

use Spryker\Zed\Product\Business\Exception\MissingProductException;

class ProductConcreteTouch extends AbstractProductTouch implements ProductConcreteTouchInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcrete($idProductConcrete)
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idProductConcrete): void {
            $this->executeTouchProductConcreteTransaction($idProductConcrete);
        });
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    protected function executeTouchProductConcreteTransaction(int $idProductConcrete): void
    {
        $concreteProductEntity = $this->getProductEntity($idProductConcrete);

        $this->touchConcreteByStatus($concreteProductEntity);
        $this->touchAbstractByStatus($concreteProductEntity->getFkProductAbstract());
    }

    /**
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProductEntity($idProductConcrete)
    {
        $concreteProductEntity = $this->productQueryContainer
            ->queryProduct()
            ->findOneByIdProduct($idProductConcrete);

        if (!$concreteProductEntity) {
            throw new MissingProductException(sprintf(
                'Concrete product with id %d could not be found.',
                $idProductConcrete
            ));
        }

        return $concreteProductEntity;
    }
}
